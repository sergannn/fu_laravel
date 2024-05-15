<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserAuthController extends Controller
{
            //
        public function login(Request $request)
        {
            //echo 'hello';
            $loginUserData = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|min:1'
            ]);

            $user = User::where('email', $loginUserData['email'])->first();

            if (!$user ||!Hash::check($loginUserData['password'], $user->password)) {
                return response()->json([
                    'message' => 'Invalid Credentials'
                ], 401);
            }

            $token = $user->createToken($user->name. '-AuthToken')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'user_id' => $user->id, 
            ]);
}

        public function logout(Request $request)
        {
        // Retrieve the current authenticated user
        $user = Auth::user();

        // Revoke the access token
        $user->currentAccessToken()->delete();

        // Optionally, clear the authentication guard to log out the user completely
        Auth::guard('api')->logout();

        return response()->json([
        'message' => 'Logged out successfully'
        ]);
        }
}
