<?php

use App\Http\Controllers\Api\Invoice\InvoiceController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::post(
        '/contracts/{contract}/invoices',
        [InvoiceController::class, 'store']
    );

    Route::get(
        '/contracts/{contract}/invoices',
        [InvoiceController::class, 'index']
    );

    Route::get(
        '/invoices/{invoice}',
        [InvoiceController::class, 'show']
    );

    Route::post(
        '/invoices/{invoice}/payments',
        [InvoiceController::class, 'recordPayment']
    );

    Route::get(
        '/contracts/{contract}/summary',
        [InvoiceController::class, 'summary']
    );

});
   Route::post('/login', function (Request $request) {

    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    return response()->json([
        'token' => $user->createToken('invoice-api')->plainTextToken
    ]);
});