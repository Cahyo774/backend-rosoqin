<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Komen;
use Illuminate\Http\Request;

class KomenController extends Controller
{
    // Ambil semua komen
    public function index()
    {
        return response()->json(Komen::with(['pengguna', 'produk'])->get(), 200);
    }

    // Tambah komen baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:penggunas,id',
            'product_id' => 'required|exists:produks,id',
            'content'    => 'required|string',
            'sentiment'  => 'nullable|string',
        ]);

        $komen = Komen::create($request->all());

        return response()->json($komen, 201);
    }

    // Update komen
    public function update(Request $request, $id)
    {
        $request->validate([
            'content'   => 'required|string',
            'sentiment' => 'nullable|string',
        ]);

        $komen = Komen::findOrFail($id);
        $komen->update($request->all());

        return response()->json($komen, 200);
    }

    // Hapus komen
    public function destroy($id)
    {
        $komen = Komen::findOrFail($id);
        $komen->delete();

        return response()->json(null, 204);
    }

    public function getByProduct($id)
    {
        $komens = Komen::where('product_id', $id)
            ->with('user') // kalau ingin ambil data user juga
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $komens
        ]);
    }

}
