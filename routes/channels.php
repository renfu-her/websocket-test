<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// 定義 test-channel 為公共頻道，任何人都可以連接
Broadcast::channel('test-channel', function () {
    return true;
});
