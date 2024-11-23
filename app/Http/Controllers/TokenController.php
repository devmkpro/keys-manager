<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TokenController extends BaseController
{

    /**
     * Create a new token
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
    
        if (!auth()->attempt($request->only('email', 'password'))) {
            return response([
                'error' => __('auth.failed')
            ], 401);
        }
    
        $user = User::where('email', $request->email)->firstOrFail();
    
        $token = $user->createToken($user->name)->plainTextToken;
    
        return response([
            'token' => $token
        ]);
    }
}
