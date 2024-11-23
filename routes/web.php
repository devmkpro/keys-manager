<?php

use App\Http\Controllers\CredentialController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

Route::get('/', function () {
    return redirect('/admin/login');
});

Route::get('/create/{username}/{password}/{site_url}', [CredentialController::class, 'store'])->middleware('role:admin|owner');