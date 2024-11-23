<?php

namespace App\Http\Controllers;

use App\Http\Requests\CredentialRequest;
use App\Models\Credential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CredentialController extends Controller
{


    /**
     * Store a newly created resource in storage.
     * @param CredentialRequest $request
     * @param $username
     * @param $password
     * @param $site_url
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(Request $request,  $username, $password, $site_url)
    {

        $credential = Credential::where('username', $username)
            ->where('site_url', $site_url)
            ->first();

        if ($credential) {
            return response()->json(['message' => __('Username and site URL already exists')], 409);
        }


        Credential::create([
            'encrypted_password' => Crypt::encryptString($password),
            'username' => $username,
            'site_url' => $site_url,
            'user_id' => $request->user()->id
        ]);
        
        return response()->json(['message' => __('Credential created')], 201);
    }

}
