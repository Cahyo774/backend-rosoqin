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
        $produks = Produk::with('user')->get();

        $produks->map(function($produk) {
            $produk->photo_url = $produk->photo ? asset('storage/' . $produk->photo) : null;
            return $produk;
        });

        return response()->json($produks);
    }

    public function show($id)
    {
        $produk = Produk::with('user')->findOrFail($id);
        $produk->photo_url = $produk->photo ? asset('storage/' . $produk->photo) : null;
        return response()->json($produk);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'category'    => 'required|string|max:100', // ✅ UBAH: category_id -> category
            'address'     => 'required|string|max:500', // ✅ UBAH: nullable -> required
            'latitude'    => 'required|numeric|between:-90,90', // ✅ UBAH: nullable -> required
            'longitude'   => 'required|numeric|between:-180,180', // ✅ UBAH: nullable -> required
            'price'       => 'nullable|numeric|min:0',
            'status'      => 'required|in:available,unavailable',
            'photo'       => 'required|image|mimes:jpg,jpeg,png|max:2048', // ✅ UBAH: nullable -> required
        ]);

        try {
            // Handle file upload
            if (!$request->hasFile('photo')) {
                return response()->json([
                    'message' => 'Foto produk wajib diunggah'
                ], 422);
            }

            $path = $request->file('photo')->store('produk', 'public');

            // Debug data sebelum create
            logger('📝 DATA YANG AKAN DISIMPAN:', [
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category, // ✅ UBAH: category_id -> category
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'price' => $request->price,
                'status' => $request->status,
                'photo' => $path,
                'id_user' => auth()->id()
            ]);

            $produk = Produk::create([
                'title'       => $request->title,
                'description' => $request->description,
                'category'    => $request->category, // ✅ UBAH: category_id -> category
                'address'     => $request->address,
                'latitude'    => $request->latitude,
                'longitude'   => $request->longitude,
                'price'       => $request->price ?? 0.00,
                'status'      => $request->status,
                'photo'       => $path,
                'id_user'     => auth()->id(),
            ]);

            // Reload dengan relationship
            $produk->load('user');
            $produk->photo_url = asset('storage/' . $produk->photo);

            logger('✅ PRODUK BERHASIL DIBUAT:', $produk->toArray());

            return response()->json([
                'message' => 'Produk berhasil dibuat',
                'data' => $produk
            ], 201);

        } catch (\Exception $e) {
            logger('❌ ERROR CREATE PRODUK:', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Gagal membuat produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        // Authorization - hanya pemilik yang bisa update
        if ($produk->id_user !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized - Anda bukan pemilik produk ini'
            ], 403);
        }

        $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'sometimes|required|string|max:100', // ✅ UBAH: category_id -> category
            'address'     => 'sometimes|required|string|max:500',
            'latitude'    => 'sometimes|required|numeric|between:-90,90',
            'longitude'   => 'sometimes|required|numeric|between:-180,180',
            'price'       => 'sometimes|required|numeric|min:0',
            'status'      => 'sometimes|required|in:available,unavailable',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $data = $request->only(['title', 'description', 'category', 'address', 'latitude', 'longitude', 'price', 'status']); // ✅ UBAH: tambahkan category

            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if ($produk->photo && Storage::disk('public')->exists($produk->photo)) {
                    Storage::disk('public')->delete($produk->photo);
                }
                $data['photo'] = $request->file('photo')->store('produk', 'public');
            }

            $produk->update($data);
            $produk->load('user');
            $produk->photo_url = $produk->photo ? asset('storage/' . $produk->photo) : null;

            return response()->json([
                'message' => 'Produk berhasil diupdate',
                'data' => $produk
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupdate produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Authorization - hanya pemilik yang bisa hapus
        if ($produk->id_user !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized - Anda bukan pemilik produk ini'
            ], 403);
        }

        try {
            // Hapus foto jika ada
            if ($produk->photo && Storage::disk('public')->exists($produk->photo)) {
                Storage::disk('public')->delete($produk->photo);
            }

            $produk->delete();

            return response()->json([
                'message' => 'Produk berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Method untuk mendapatkan produk milik user yang login
    public function myProducts()
    {
        $produks = Produk::where('id_user', auth()->id())->get();

        $produks->map(function($produk) {
            $produk->photo_url = $produk->photo ? asset('storage/' . $produk->photo) : null;
            return $produk;
        });

        return response()->json([
            'data' => $produks
        ]);
    }
}
