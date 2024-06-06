<?php

use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/setup', function () {
    $credentials = [
        'email' => 'admin@gmail.com',
        'password' => 'password',
    ];

    if (!Auth::attempt($credentials)) {
        $user = new User();

        $user->create([
            'name' => 'Admin',
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),
        ]);
    }

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        $adminToken = $user->createToken('admin-token', ['create', 'update', 'delete']);
    }
});


Route::prefix("v1")->group(function () {
    Route::apiResource("customers", CustomerController::class);
    Route::apiResource("invoices", InvoiceController::class);

    Route::post('/invoices/bulk', [InvoiceController::class, 'bulkStore']);
});
