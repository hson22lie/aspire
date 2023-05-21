<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/token', [AuthController::class, 'generateToken']);
Route::post('/admin/login', [AuthController::class, 'loginAdmin']);

Route::group(['prefix' => 'loan', 'middleware'  => ['user']], function () {
    Route::get('/', [LoanController::class, 'get']);
    Route::post('/', [LoanController::class, 'create']);
    Route::get('/{loanID}', [LoanController::class, 'detail']);
});
