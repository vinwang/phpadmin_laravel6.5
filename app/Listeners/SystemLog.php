<?php

namespace App\Listeners;

use App\Models\SystemLog as log;
use App\Events\SystemLog as SystemLogEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SystemLog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SystemLog  $event
     * @return void
     */
    public function handle(SystemLogEvent $event)
    {
        $log = new Log;
        $log->comment = $event->log;
        $log->user_id = $event->adminId ?: auth('admin')->user()->id;

        $log->save();
    }
}
