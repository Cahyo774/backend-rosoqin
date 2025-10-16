<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jemputan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JemputanController extends Controller
{
    public function index()
    {
        return Jemputan::with('pengguna')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'alamat' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $jemputan = Jemputan::create([
            'user_id' => Auth::id(),
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'pending',
        ]);

        return response()->json($jemputan, 201);
    }

    public function update(Request $request, $id)
    {
        $jemputan = Jemputan::findOrFail($id);

        $request->validate([
            'status' => 'in:pending,proses,selesai',
        ]);

        $jemputan->update($request->all());

        return response()->json($jemputan, 200);
    }

    public function destroy($id)
    {
        $jemputan = Jemputan::findOrFail($id);
        $jemputan->delete();

        return response()->json(null, 204);
    }
}
