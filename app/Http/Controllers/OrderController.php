<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function generateSnapToken(Request $request)
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $user = User::find($request->user_id); //  mnecari user yang terdapat di table

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => $request->total,
            ),
            'customer_details' => array(
                'first_name' => $user->name,
                'alamat' => $user->alamat,
                'phone' => $user->notelp,
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return response()->json([
            'token' => $snapToken,
            'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken,
        ]);
    }

    public function index()
    {
        $orders = Order::with('user')->with('product')->get();

        return response()->json([
            'orders' => $orders,
        ]);
    }

    public function showBySnapToken()
    {
        $orders = Order::with('user')->with('product')->get();
        $groupedOrders = [];

        foreach ($orders as $order) {
            $snapToken = $order->snap_token;

            if (!isset($groupedOrders[$snapToken])) {
                $groupedOrders[$snapToken] = [
                    'orders' => [],
                    'total_price' => 0,
                    'service_fee' => 0,
                    'total_quantity' => 0,
                ];
            }

            // Hitung biaya layanan (10% dari total harga pesanan)
            $serviceFee = $order->price * 0.1; // Biaya layanan sebesar 10% dari harga pesanan
            $groupedOrders[$snapToken]['service_fee'] += $serviceFee;

            $groupedOrders[$snapToken]['orders'][] = $order;
            $groupedOrders[$snapToken]['total_price'] += $order->price + $serviceFee; // Ubah 'price' sesuai field harga pada model Order
            $groupedOrders[$snapToken]['total_quantity'] += $order->quantity; // Tambahkan quantity ke total quantity

            // Jika belum ada image yang diset, gunakan image dari order pertama
            if (!isset($groupedOrders[$snapToken]['image'])) {
                $groupedOrders[$snapToken]['image'] = $order->product->img;
            }

            // Daopatkan snaptoken dari data
            if (!isset($groupedOrders[$snapToken]['snap_token'])) {
                $groupedOrders[$snapToken]['snap_token'] = $order->snap_token;
            }
        }

        // Ubah associative array menjadi array numerik
        $result = array_values($groupedOrders);

        return response()->json([
            'orders' => $result,
        ]);
    }

    public function showBySnapTokenId($snapToken)
    {
        $orders = Order::with('user')->with('product')->where('snap_token', $snapToken)->get();
        $groupedOrders = [];

        foreach ($orders as $order) {
            $snapToken = $order->snap_token;

            if (!isset($groupedOrders[$snapToken])) {
                $groupedOrders[$snapToken] = [
                    'orders' => [],
                    'total_price' => 0,
                    'service_fee' => 0,
                    'total_quantity' => 0,
                ];
            }

            // Hitung biaya layanan (10% dari total harga pesanan)
            $serviceFee = $order->price * 0.1; // Biaya layanan sebesar 10% dari harga pesanan
            $groupedOrders[$snapToken]['service_fee'] += $serviceFee;

            $groupedOrders[$snapToken]['orders'][] = $order;
            $groupedOrders[$snapToken]['total_price'] += $order->price + $serviceFee; // Ubah 'price' sesuai field harga pada model Order
            $groupedOrders[$snapToken]['total_quantity'] += $order->quantity; // Tambahkan quantity ke total quantity

            // Jika belum ada image yang diset, gunakan image dari order pertama
            if (!isset($groupedOrders[$snapToken]['image'])) {
                $groupedOrders[$snapToken]['image'] = $order->product->img;
            }

            // Daopatkan snaptoken dari data
            if (!isset($groupedOrders[$snapToken]['snap_token'])) {
                $groupedOrders[$snapToken]['snap_token'] = $order->snap_token;
            }
        }

        // Ubah associative array menjadi array numerik
        $result = array_values($groupedOrders);

        return response()->json([
            'orders' => $result,
        ]);
    }

    public function store(Request $request)
    {
        $order = Order::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'size' => $request->size,
            'color' => $request->color,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'snap_token' => $request->snap_token,
            'status' => 'Unpaid',
        ]);

        return response()->json([
            'order' => $order,
        ]);
    }
}
