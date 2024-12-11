<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        try{
            $users = User::all();
            if(!$users){
                return response()->json([
                    'error' => 'Data users is empty',
                ],404);
            }
            return response()->json([
                'message' => 'successfully retrieve data user',
                'data' => $users,
            ],
            200);
        } catch (\Exception $error){
            return response()->json([
                'error' => $error->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        try{
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            return response()->json([
                'message' => 'successfully retrieve user with ' .$id,
                'data' => $user,
                ]
            ,200);
        } catch (\Exception $error){
            return response()->json([
                'error' => $error->getMessage()
            ],500);
        }
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        try{

            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:users,email,',
                'password' => 'sometimes|required|string|min:8|confirmed',
                'roles' => 'sometimes|in:user,admin'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $validated = $validator->validated();

            $user->update([
                'name' => $validated['name'] ?? $user->name,
                'email' => $validated['email'] ?? $user->email,
                'password' => isset($validated['password']) ? Hash::make($validated['password']) : $user->password,
                'roles' => $validated['roles'] ?? $user->roles,
            ]);

            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user
            ], 200);
        } catch(\Exception $error){
            return response()-> json([
                'error' => $error->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        try{
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            if($user->status != 2  ){
                return response()->json(['error' => 'User account is still active'],400);
            }
            $user->delete();

            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch(\Exception $error){
            return response()->json([
                'error' => $error->getMessage(),
            ],500);
        }
    }
}
