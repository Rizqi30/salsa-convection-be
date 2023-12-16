<?php

namespace App\Http\Controllers;

use App\Models\CartModel;
use Illuminate\Http\Request;

class CartsController extends Controller
{
    public function getCart()
    {
        $cart = CartModel::with(['products'])->get();

        $cartWithProductData = $cart->map(function ($cartItem) {
            $productId = $cartItem->product_id;
            $productData = $cartItem->productById($productId);

            return [
                'cart_id' => $cartItem->id,
                'product_id' => $productId,
                'size' => $cartItem->size,
                'color' => $cartItem->color,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
                'product_data' => $productData,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'List Cart',
            'data' => $cartWithProductData,
        ], 200);
    }

    public function addToCart(Request $request) //add to cart
    {
        $cart = new CartModel();
        $cart->user_id = $request->user_id;
        $cart->product_id = $request->product_id;
        $cart->size = $request->size;
        $cart->color = $request->color;
        $cart->quantity = $request->quantity;
        $cart->price = $request->price;
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan ke keranjang',
            'data' => $cart
        ], 200);
    }

    public function updateCart(Request $request, $id) // update to cart
    {
        $cart = CartModel::find($id);
        $cart->user_id = $request->user_id;
        $cart->product_id = $request->product_id;
        $cart->quantity  = $request->quantity;
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil di update',
            'data' => $cart
        ], 200);
    }

    public function deleteCart($id) //delete to cart
    {
        $cart = CartModel::find($id);
        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil di hapus',
            'data' => $cart
        ], 200);
    }
}
