<?php

namespace App\Http\Controllers\API\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Penjual hanya bisa melihat produk miliknya
    public function index(Request $request)
    {
        $products = Product::where('user_id', $request->user()->id)->get();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'kategori_id' => 'required|exists:kategori,id',
            'wilayah_id' => 'required|exists:wilayah,id',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Simpan gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('produk', 'public');
        }

        $product = Product::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'kategori_id' => $request->kategori_id,
            'wilayah_id' => $request->wilayah_id,
            'image' => $imagePath,
        ]);

        return response()->json(['message' => 'Produk berhasil ditambahkan', 'product' => $product], 201);
    }

    public function show($id, Request $request)
    {
        $product = Product::where('id', $id)->where('user_id', $request->user()->id)->first();

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::where('id', $id)->where('user_id', $request->user()->id)->first();

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric',
            'stock' => 'sometimes|integer',
            'kategori_id' => 'sometimes|exists:kategori,id',
            'wilayah_id' => 'sometimes|exists:wilayah,id',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('produk', 'public');
            $product->image = $imagePath;
        }

        $product->update($request->only([
            'name', 'description', 'price', 'stock', 'kategori_id', 'wilayah_id'
        ]));

        return response()->json(['message' => 'Produk berhasil diperbarui', 'product' => $product]);
    }

    public function destroy($id, Request $request)
    {
        $product = Product::where('id', $id)->where('user_id', $request->user()->id)->first();

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Produk berhasil dihapus']);
    }
}
