<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PenjualController extends Controller
{
    public function masuk(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || $user->role !== 'penjual' || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email, password, atau peran tidak cocok'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Masuk sebagai penjual berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
