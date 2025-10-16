<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|string|email|unique:penggunas',
        'password' => 'required|string|min:6',
        'role' => 'in:user,admin'
    ]);

    $pengguna = Pengguna::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role ?? 'user',
    ]);

    // Buat token langsung setelah registrasi
    //$token = $pengguna->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Registrasi berhasil',
        'user' => $pengguna,
        //'token' => $token
    ], 201);
}



    // LOGIN
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    $pengguna = Pengguna::where('email', $request->email)->first();

    if (!$pengguna || !Hash::check($request->password, $pengguna->password)) {
        return response()->json(['message' => 'Email atau password salah'], 401);
    }

    // Buat token
    $token = $pengguna->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login berhasil',
        'user' => $pengguna,
        'token' => $token,
    ], 200);
}

    // LOGOUT
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}

