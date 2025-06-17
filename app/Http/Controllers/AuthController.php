<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth; // استدعاء JWTAuth

class AuthController extends Controller
{
    // تسجيل مستخدم جديد
    public function register(Request $request)
{
    $validated = $request->validate([
        'username' => 'required|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'fname' => 'required',
        'lname' => 'required',
    ]);

    $user = User::create([
        'username' => $validated['username'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'fname' => $validated['fname'],
        'lname' => $validated['lname'],
    ]);

    $token = JWTAuth::fromUser($user);

    return response()->json([
        'user' => $user,
        'token' => $token,
    ], 201);
}



    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        // ✅ استخدم guard الخاص بـ JWT
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::guard('api')->user(); // ✅ استرجاع المستخدم لتحديد الدور

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'role' => $user->role,
        ]);
    }







    // تسجيل الخروج (اختياري)
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logged out successfully']);
    }

    // استرجاع بيانات المستخدم الحالي (اختياري)
    public function me()
    {
        return response()->json(JWTAuth::user());
    }
}
