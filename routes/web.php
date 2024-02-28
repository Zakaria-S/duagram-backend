<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomePageController;
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

    Route::post('/p', [PostController::class, 'createPost']); //beres
    Route::get('/p/{id}', [PostController::class, 'showPost']); //beres
    Route::put('/p/{id}', [PostController::class, 'editPost']); //beres
    Route::delete('/p/{id}', [PostController::class, 'deletePost']); //beres

    Route::post('/p/{id}/like', [PostCOntroller::class, 'likePost']);
    Route::post('/p/{id}/unlike', [PostController::class], 'unlikePost');

    Route::get('/p/{id}/comment', [CommentController::class, 'getCommentsFromPost']);
    Route::post('/p/{id}/comment', [CommentController::class, 'buatComment']);
    Route::get('/comment/{id}', [CommentController::class, 'showComment']);
    Route::put('/comment/{id}', [CommentController::class, 'editComment']);
    Route::delete('/comment/{id}', [CommentController::class, 'deleteComment']);

    Route::post('/comment/{id}/like', [CommentController::class, 'likeComment']);
    Route::post('/comment/{id}/unlike', [CommentController::class, 'unlikeComment']);


    Route::get('/{username}', [UserController::class, 'userProfile']); //beres
    Route::post('/{username}/follow', [UserController::class, 'follow']); //beres
    Route::post('/{username}/unfollow', [UserController::class, 'unfollow']); //beres

    Route::put('/accounts/edit', [UserController::class, 'editProfile']);

    Route::get('/', [HomePageController::class, 'index']);
});



require __DIR__ . '/auth.php';
