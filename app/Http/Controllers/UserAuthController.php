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
    ]);
}
}
