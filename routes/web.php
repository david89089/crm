<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


//API
use App\Http\Controllers\API\v1\ChatController as ApiChatController;
use App\Http\Controllers\API\v1\ChatMessageController as ApiChatMessageController;
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

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/chat/{id}', [ChatController::class, 'index'])->name('chat.index');

    //API
    Route::post('/chat', [ApiChatController::class, 'store'])->name('chat.store');
    Route::post('/check/', [ApiChatController::class, 'check'])->name('chat.check');
    Route::post('/message', [ApiChatMessageController::class, 'store'])->name('chat.message.store');
});
