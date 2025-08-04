<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BroadcastTestController;

Route::get('/', function () {
    return view('welcome');
});

// 廣播測試路由
Route::prefix('broadcast')->group(function () {
    Route::get('/test', [BroadcastTestController::class, 'index'])->name('broadcast.test');
    Route::get('/debug', function () {
        return view('echo-debug');
    })->name('broadcast.debug');
    Route::post('/trigger', [BroadcastTestController::class, 'trigger'])->name('broadcast.trigger');
    Route::post('/trigger-multiple', [BroadcastTestController::class, 'triggerMultiple'])->name('broadcast.trigger-multiple');
});
