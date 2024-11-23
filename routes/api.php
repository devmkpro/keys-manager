<?php

use App\Http\Controllers\CredentialController;
use App\Http\Controllers\TokenController;
use Illuminate\Support\Facades\Route;


Route::post('tokens/create', [TokenController::class, 'store']);
