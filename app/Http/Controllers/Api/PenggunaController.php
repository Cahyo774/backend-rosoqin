<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:penggunas',
            'password' => 'required|string|min:6',
            'role' => 'in:user,admin'
        ]);

        $pengguna = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user',
        ]);

        return response()->json($pengguna, 201);
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $pengguna = User::findOrFail($id);

        $pengguna->update([
            'name' => $request->name ?? $pengguna->name,
            'email' => $request->email ?? $pengguna->email,
            'password' => $request->password ? Hash::make($request->password) : $pengguna->password,
            'role' => $request->role ?? $pengguna->role,
        ]);

        return response()->json($pengguna, 200);
    }

    public function destroy($id)
    {
        $pengguna = User::findOrFail($id);
        $pengguna->delete();

        return response()->json(null, 204);
    }
}
