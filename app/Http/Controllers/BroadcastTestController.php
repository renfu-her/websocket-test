<?php

namespace App\Http\Controllers;

use App\Events\TestEvent;
use Illuminate\Http\Request;

class BroadcastTestController extends Controller
{
    /**
     * 顯示廣播測試頁面
     */
    public function index()
    {
        return view('broadcast-test');
    }

    /**
     * 觸發測試廣播
     */
    public function trigger(Request $request)
    {
        $message = $request->input('message', '測試廣播訊息');
        
        // 觸發廣播事件
        broadcast(new TestEvent($message));
        
        return response()->json([
            'success' => true,
            'message' => '廣播已觸發',
            'data' => [
                'message' => $message,
                'timestamp' => now()->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    /**
     * 觸發多個測試廣播
     */
    public function triggerMultiple()
    {
        $messages = [
            '第一則測試訊息',
            '第二則測試訊息',
            '第三則測試訊息',
        ];

        foreach ($messages as $index => $message) {
            // 延遲觸發，讓訊息有時間間隔
            sleep(1);
            broadcast(new TestEvent($message));
        }

        return response()->json([
            'success' => true,
            'message' => '已觸發 ' . count($messages) . ' 則廣播訊息',
        ]);
    }
}
