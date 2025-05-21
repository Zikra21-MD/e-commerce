<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    public function daftar(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'in:admin,penjual,pembeli',
        ]);

        $pengguna = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'pembeli',
        ]);

        return response()->json([
            'pesan' => 'Pendaftaran berhasil',
            'pengguna' => $pengguna,
        ], 201);
    }

    public function masuk(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $pengguna = User::where('email', $request->email)->first();

        if (!$pengguna || !Hash::check($request->password, $pengguna->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau kata sandi salah.'],
            ]);
        }

        $token = $pengguna->createToken('token_akses')->plainTextToken;

        return response()->json([
            'token_akses' => $token,
            'tipe_token' => 'Bearer',
            'pengguna' => $pengguna,
        ]);
    }

    public function keluar(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'pesan' => 'Berhasil keluar'
        ]);
    }
}

