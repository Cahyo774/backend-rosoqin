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

        $produks->map(function ($produk) {
            $produk->photo_url = $produk->photo ? asset('storage/' . $produk->photo) : null;
            $produk->user_id = $produk->id_user;
            return $produk;
        });

        return response()->json($produks);
    }

    public function show($id)
    {
        // 🔥 Ambil produk + relasi user
        $produk = Produk::with('user')->findOrFail($id);

        // 🔥 Tambahkan URL foto
        $produk->photo_url = $produk->photo ? asset('storage/' . $produk->photo) : null;
        $produk->user_id = $produk->id_user;


        return response()->json($produk);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'category'    => 'required|string|max:100',
            'address'     => 'required|string|max:500',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
            'price'       => 'nullable|numeric|min:0',
            'status'      => 'required|in:available,unavailable',
            'photo'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if (!$request->hasFile('photo')) {
            return response()->json(['message' => 'Foto produk wajib diunggah'], 422);
        }

        try {
            $path = $request->file('photo')->store('produk', 'public');

            // 🔥 Simpan produk dengan id_user
            $produk = Produk::create([
                'title'       => $request->title,
                'description' => $request->description,
                'category'    => $request->category,
                'address'     => $request->address,
                'latitude'    => $request->latitude,
                'longitude'   => $request->longitude,
                'price'       => $request->price ?? 0.00,
                'status'      => $request->status,
                'photo'       => $path,
                'id_user'     => auth()->id(),
            ]);

            $produk->load('user');
            $produk->photo_url = asset('storage/' . $produk->photo);
            $produk->user_id = $produk->id_user;


            return response()->json([
                'message' => 'Produk berhasil dibuat',
                'data'    => $produk
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat produk',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        // 🔥 Hanya pemilik yang boleh edit
        if ($produk->id_user !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'sometimes|required|string|max:100',
            'address'     => 'sometimes|required|string|max:500',
            'latitude'    => 'sometimes|required|numeric|between:-90,90',
            'longitude'   => 'sometimes|required|numeric|between:-180,180',
            'price'       => 'sometimes|required|numeric|min:0',
            'status'      => 'sometimes|required|in:available,unavailable',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $data = $request->only([
                'title', 'description', 'category', 'address',
                'latitude', 'longitude', 'price', 'status'
            ]);

            // 🔥 Ganti foto jika diupload baru
            if ($request->hasFile('photo')) {
                if ($produk->photo && Storage::disk('public')->exists($produk->photo)) {
                    Storage::disk('public')->delete($produk->photo);
                }
                $data['photo'] = $request->file('photo')->store('produk', 'public');
            }

            $produk->update($data);
            $produk->load('user');
            $produk->photo_url = $produk->photo ? asset('storage/' . $produk->photo) : null;
            $produk->user_id = $produk->id_user;


            return response()->json([
                'message' => 'Produk berhasil diupdate',
                'data'    => $produk
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupdate produk',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // 🔥 Hanya pemilik boleh menghapus
        if ($produk->id_user !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            if ($produk->photo && Storage::disk('public')->exists($produk->photo)) {
                Storage::disk('public')->delete($produk->photo);
            }

            $produk->delete();

            return response()->json(['message' => 'Produk berhasil dihapus']);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus produk',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function myProducts()
    {
        $produks = Produk::where('id_user', auth()->id())
                         ->with('user')
                         ->get();

        $produks->map(function ($produk) {
            $produk->photo_url = asset('storage/' . $produk->photo);
            return $produk;
        });

        return response()->json(['data' => $produks]);
    }
}
