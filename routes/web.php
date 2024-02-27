<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/p/{uniqueName}', [PostController::class, 'getPost']);
    // Route::put('/p/{uniqueName}', [PostController::class, 'editPost']);
    Route::get('/post', [PostController::class, 'getAllPost']); //sementara, ntar juga dihapus
    Route::post('/post', [PostController::class, 'buatPostBaru']);
    Route::put('/p/{id}', [PostController::class, 'editPost']);
    Route::get('/{username}', [UserController::class, 'userProfile']);
});



require __DIR__ . '/auth.php';
