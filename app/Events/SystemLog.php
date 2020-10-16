<?php

namespace App\Events;

use App\Models\SystemLog as log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SystemLog
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $log;

    public $adminId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($log, $adminId = 0)
    {
        $this->log = $log;

        $this->adminId = $adminId;
    }
}
