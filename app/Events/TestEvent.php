<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $timestamp;

    /**
     * Create a new event instance.
     */
    public function __construct($message = '測試廣播訊息')
    {
        $this->message = $message;
        $this->timestamp = now()->format('Y-m-d H:i:s');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('test-channel'),
        ];
    }

    /**
     * 廣播事件名稱
     */
    public function broadcastAs(): string
    {
        return 'test-message';
    }

    /**
     * 廣播資料
     */
    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'timestamp' => $this->timestamp,
            'server_time' => now()->toISOString(),
        ];
    }
}
