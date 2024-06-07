<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/setup', function (Request $request) {

    $credentials = [
        'name' => 'Admin',
        'email' => 'admin@gmail.com',
        'password' => 'password',
    ];

    if (!Auth::attempt($credentials)) {

        $user = new User();

        $user->create([
            'name' =>   $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),
        ]);
    }

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        $adminToken = $user->createToken('admin-token', ['create', 'update', 'delete']);
        $updateToken = $user->createToken('update-token', ['create', 'update']);
        $basicToken = $user->createToken('basic-token');

        return [
            'admin' => $adminToken->plainTextToken,
            'update' => $updateToken->plainTextToken,
            'basic' => $basicToken->plainTextToken,
            'user' => $user,
        ];
    }
});

Route::get('/delete-token', function (Request $request) {
    return auth()->user()->tokens()->delete();
});
