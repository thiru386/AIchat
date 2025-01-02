<?php

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
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::post('/chat/send-message', [ChatController::class, 'sendMessage']);
Route::get('/chat/conversations', [ChatController::class, 'getConversations']);



Route::get('/chat', function () {
    return view('chat');
});