<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// class AuthController extends Controller
// {
    // REGISTER
//     public function register(Request $request)
// {
//     $request->validate([
//         'name' => 'required|string',
//         'email' => 'required|string|email|unique:penggunas',
//         'password' => 'required|string|min:6',
//         'role' => 'in:user,admin'
//     ]);

//     $pengguna = Pengguna::create([
//         'name' => $request->name,
//         'email' => $request->email,
//         'password' => Hash::make($request->password),
//         'role' => $request->role ?? 'user',
//     ]);

    // Buat token langsung setelah registrasi
    //$token = $pengguna->createToken('auth_token')->plainTextToken;

    // return response()->json([
        // 'message' => 'Registrasi berhasil',
        // 'user' => $pengguna,
        //'token' => $token
    // ], 201);
// }



    // LOGIN
    // public function login(Request $request)
// {
//     $request->validate([
//         'email' => 'required|string|email',
//         'password' => 'required|string',
//     ]);

//     $pengguna = Pengguna::where('email', $request->email)->first();

//     if (!$pengguna || !Hash::check($request->password, $pengguna->password)) {
//         return response()->json(['message' => 'Email atau password salah'], 401);
//     }

    // Buat token
    // $token = $pengguna->createToken('auth_token')->plainTextToken;

//     return response()->json([
//         'message' => 'Login berhasil',
//         'user' => $pengguna,
//         'token' => $token,
//     ], 200);
// }

    // LOGOUT
//     public function logout(Request $request)
//     {
//         $request->user()->tokens()->delete();

//         return response()->json([
//             'message' => 'Logout berhasil'
//         ]);
//     }
// }



// AuthController.php - Update menggunakan model User
use App\Models\User; // Ganti Pengguna dengan User

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users', // Ganti 'penggunas' dengan 'users'
            'password' => 'required|string|min:6',
            'role' => 'in:user,admin'
        ]);

        $user = User::create([ // Ganti $pengguna dengan $user
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user',
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first(); // Ganti Pengguna dengan User

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
