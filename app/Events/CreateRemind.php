<?php

namespace App\Events;

use App\Models\Remind;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateRemind
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var [type]
     */
    public $remind;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Remind $remind)
    {
        //
        $this->remind = $remind;
    }
}
