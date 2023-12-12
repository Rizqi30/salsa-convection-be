<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductsModel;

class ProductsController extends Controller
{
    public function index() //get all products
    {
        $products = ProductsModel::all();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Produk',
            'data' => $products
        ], 200);
    }

    public function show($id) //get a single product
    {
        $product = ProductsModel::find($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail Produk',
            'data' => $product
        ], 200);
    }

    public function store(Request $request) //create a new product
    {

        if ($request->file('img')) {
            $image = $request->file('img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gambar tidak ditemukan.',
            ], 400);
        }

        $product = ProductsModel::create([
            'img' => $imageName,
            'name' => $request->name,
            'price' => $request->price,
            'size' => $request->size,
            'color' => $request->color,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'status' => $request->status,
            'sold' => $request->sold,
        ]);

        if ($product) {
            return response()->json([
                'success' => true,
                'message' => 'Produk Berhasil Disimpan!',
                'data' => $product
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Produk Gagal Disimpan!',
            ], 400);
        }
    }

    public function update(Request $request, $id) //update a product
    {
        $product = ProductsModel::find($id)->update([
            'img' => $request->img,
            'name' => $request->name,
            'price' => $request->price,
            'size' => $request->size,
            'color' => $request->color,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'status' => $request->status,
            'sold' => $request->sold,
        ]);

        if ($product) {
            return response()->json([
                'success' => true,
                'message' => 'Produk Berhasil Diupdate!',
                'data' => $product
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Produk Gagal Diupdate!',
            ], 500);
        }
    }

    public function destroy($id) //delete a product
    {
        $product = ProductsModel::find($id);
        $delete = $product->delete();

        if ($delete) {
            return response()->json([
                'success' => true,
                'message' => 'Produk Berhasil Dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Produk Gagal Dihapus!',
            ], 500);
        }
    }
}
