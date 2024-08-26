<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request)
    {
        $userId = $request->route('id');
        $bool = $request->route("bool");
        $user = Auth::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->email_verified_at = $bool;
        $user->save();

        event(new Verified($user));

        return response()->json(['message' => 'Email verified successfully']);
    }
}
