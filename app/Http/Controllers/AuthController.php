<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'phone_number' => $request['phone_number']
        ]);
        return response()->json($user, 201);
    }
    public function login(AuthRequest $request)
    {
        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
            Auth::user()->tokens()->delete();
            $token = Auth::user()->createToken('auth_token', ['user'])->plainTextToken;

            return response()->json(['message' => 'Connexion réussie', 'token' => $token], 200);
        }

        return response()->json(['message' => 'Mauvais identifiant ou mot de passe'], 401);
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
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $status = Password::sendResetLink($request->only('email'));

        return response()->json(['status' => $status], $status === Password::RESET_LINK_SENT ? 200 : 400);
    }
    public function resetPassword($token, Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'token' => 'required'
        ]);
        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');
        $resetStatus = Password::reset($credentials, function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        });

        if ($resetStatus == Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password reset'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid token or credentials'
            ], 400);
        }
    }
    public function refresh(Request $request)
    {
        $refreshToken = $request->cookie(('refresh_token'));
        if (!$refreshToken) {
            return response()->json([
                'message' => 'Unautohorized'
            ], 401);
        }
        $token = Auth::user()->createToken('auth_token', ['user'])->plainTextToken;
        return response()->json([
            'access_token' => $token,
        ], 200);
    }
}
