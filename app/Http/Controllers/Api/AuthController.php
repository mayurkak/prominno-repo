<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid email or password.',
                ], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'role' => $user->role,
                'user' => [
                    'id'    => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Server Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function sellerLogin(Request $request)
    {
        // Validate request input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 1. Find the user by email
        $user = User::where('email', $request->email)->first();

        // 2. Validate credentials and ROLE
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password.'
            ], 401);
        }

        // 3. Strict Check: Ensure the user is a seller
        if ($user->role !== 'seller') {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. This login is for sellers only.'
            ], 403);
        }

        // 4. Generate Sanctum Token
        $token = $user->createToken('seller_auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Seller logged in successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => $user->role, // Returning Role as per requirement
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ], 200);
    }
}
