<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    //register
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response([
                'message' => 'Validation failed, please check your input again',
            ], 422);
        }
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return response([
            'user' => $user,
        ], 201);
    }
    //login
    public function login(Request $request)
    {
        try {
            $loginData = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response([
                'message' => 'Validation failed, please check your input again',
            ], 422);
        }

        if (empty($request->email) || empty($request->password)) {
            return response([
                'message' => 'Email and password are required',
            ], 400);
        }

        $user = \App\Models\User::where('email', $request->email)->first();

        if (! $user) {
            return response([
                'message' => 'Email not found',
            ], 404);
        }

        if (! Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Password is incorrect',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    //logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout success',
        ]);
    }
}
