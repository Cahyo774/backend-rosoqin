<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::all();

        $produks->map(function($produk) {
            $produk->photo_url = $produk->photo ? asset('storage/' . $produk->photo) : null;
            return $produk;
        });

        return response()->json($produks);
    }

    public function show($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->photo_url = $produk->photo ? asset('storage/' . $produk->photo) : null;
        return response()->json($produk);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'status'      => 'required|in:available,unavailable',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->hasFile('photo') ? $request->file('photo')->store('produk', 'public') : null;

        $produk = Produk::create([
            'title'       => $request->title,
            'description' => $request->description,
            'price'       => $request->price,
            'status'      => $request->status,
            'photo'       => $path,
            'user_id'     => 1,
        ]);

        $produk->photo_url = $produk->photo ? asset('storage/' . $produk->photo) : null;

        return response()->json($produk, 201);
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'sometimes|required|numeric',
            'status'      => 'required|in:available,unavailable',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($produk->photo && Storage::disk('public')->exists($produk->photo)) {
                Storage::disk('public')->delete($produk->photo);
            }
            $produk->photo = $request->file('photo')->store('produk', 'public');
        }

        $produk->update($request->only(['title', 'description', 'price', 'status', 'photo']));

        $produk->photo_url = $produk->photo ? asset('storage/' . $produk->photo) : null;

        return response()->json($produk, 200);
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->photo && Storage::disk('public')->exists($produk->photo)) {
            Storage::disk('public')->delete($produk->photo);
        }

        $produk->delete();

        return response()->json(null, 204);
    }
}
