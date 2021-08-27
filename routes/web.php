<?php

use App\Http\Controllers\Admin\LogsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


//API
use App\Http\Controllers\API\v1\ChatController as ApiChatController;
use App\Http\Controllers\API\v1\ChatMessageController as ApiChatMessageController;
use App\Http\Controllers\API\v1\Admin\UsersController as ApiAdminUsersController;
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

//Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {

    Route::prefix('/chat')->name('chat.')->group(function () {
        Route::middleware('chat')->group(function () {
            Route::get('/{chat}', [ChatController::class, 'index'])->name('index');
        });
        Route::delete('/', [ApiChatController::class, 'destroy'])->name('destroy');

        Route::post('/', [ApiChatController::class, 'store'])->name('store');

        Route::post('/message', [ApiChatMessageController::class, 'store'])
            ->name('message.store')
            ->middleware('chat.message');

        Route::middleware('control.chat')->group(function () {
            Route::post('/{chat}/add/user', [ApiChatController::class, 'addUser'])->name('add.user');
            Route::delete('/{chat}/delete/user', [ApiChatController::class, 'deleteUser'])->name('delete.user');
        });
    });

    Route::get('/settings', [SettingController::class, 'index'])->name('setting.index');

    Route::middleware(['role:manager'])->prefix('/admin')->name('admin.')->group(function () {
        Route::prefix('/users')->name('users.')->group(function () {
            Route::get('/', [UsersController::class, 'index'])->name('index');
            Route::get('/show/{id}', [UsersController::class, 'show'])->name('show');
            Route::patch('/status/{status}', [ApiAdminUsersController::class, 'updateStatus'])->name('update.status');
        });

        Route::prefix('/logs')->name('logs.')->group(function () {
            Route::get('/', [LogsController::class, 'index'])->name('index');
        });
    });
});
