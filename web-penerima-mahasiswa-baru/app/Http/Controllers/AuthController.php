<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:30',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'roles' => 'in:user,admin',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $validated = $validator->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'roles' => $validated['roles'],
            ]);

            return response()->json([
                'message' => 'User successfully registered',
                'user' => $user,
            ], 201);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage(),
            ], 500);
        }
    }
    public function verifyAccount(Request $request)
    {
        // Validasi input
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|integer|in:0,1,2',
                'IdUser' => 'required|exists:users',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $validated = $validator->validated();
            $user = User::where('IdUser', $validated['IdUser']);
            $user->update(
                ['status' => $validated['status'],'email_verified_at' => now()],
        );

            return response()->json([
                'message' => 'User successfully verified',
            ], 200);
        } catch (\Exception $errror) {
            return response()->json([
                'error' => $errror->getMessage(),
            ], 500);
        }
    }

    /**
     * Login a user.
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
            $validated = $validator->validated();
            $user = User::where('email', $validated['email'])->first();
            if (!$user || $user->status != 1) {
                return response()->json(['error' => 'Email not found or inactive'], 404);
            }
            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return response()->json(['error' => 'Invalid Password'], 401);
            }
            $token = $user->createToken('access_token')->plainTextToken;
            // Berhasil login
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
            ], 200);
        } catch (\Exception $error) {
            // Tangani error internal
            return response()->json([
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout the authenticated user.
     */
    public function logout(Request $request)
    {
        try {
            // Hapus token yang sedang digunakan
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to logout, please try again'], 500);
        }
    }
}
