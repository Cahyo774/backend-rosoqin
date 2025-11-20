<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Komen;
use Illuminate\Http\Request;

class KomenController extends Controller
{
    public function index()
    {
        return response()->json(Komen::with(['user', 'produk'])->get(), 200);
    }

    public function store(Request $request)
    {
        try {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'product_id' => 'required|exists:produks,id',
            'content' => 'required|string',
        ]);

        $komen = Komen::create($request->all());

        return response()->json([
            'message' => 'Komentar berhasil ditambahkan',
            'data' => $komen
        ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'content'   => 'required|string',
            'sentiment' => 'nullable|string',
        ]);

        $komen = Komen::findOrFail($id);
        $komen->update($request->all());

        return response()->json([
            'message' => 'Komentar berhasil diperbarui',
            'data' => $komen
        ], 200);
    }

    public function destroy($id)
    {
        $komen = Komen::findOrFail($id);
        $komen->delete();

        return response()->json(['message' => 'Komentar berhasil dihapus'], 204);
    }

    public function getByProduct($id)
    {
        $komens = Komen::where('product_id', $id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['data' => $komens], 200);
    }
}
