<?php

use Illuminate\Support\Facades\Route;
use Modules\ChatBot\Http\Controllers\ChatBotController;

Route::prefix('chatbot')->group(function () {
    Route::post('/message', [ChatBotController::class, 'handleMessage'])->name('chatbot.message');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('chatbots', ChatBotController::class)->names('chatbot');
});
