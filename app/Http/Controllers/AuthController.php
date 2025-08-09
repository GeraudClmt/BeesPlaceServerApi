<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'phone_number' => $request['phone_number']
        ]);
        return response()->json($user, 201);
    }
    public function login(AuthRequest $request)
    {
        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
            Auth::user()->tokens()->delete();
            $token = Auth::user()->createToken('auth_token', ['user'])->plainTextToken;

            return response()->json(['message' => 'Login successful', 'token' => $token], 200);
        }

        return response()->json(['message' => 'Bad identification or password'], 401);
    }
    public function user(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'message' => 'Bienvenu ' . $user->name,
            'name' => $user->name,
            'email' => $user->email
        ], 200);
    }
    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Deconnexion reussie'], 200);
    }
}
