<?php

namespace App\Listeners;

use App\Jobs\Remind;
use App\Events\CreateRemind;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RemindNotification/* implements ShouldQueue*/
{

    /**
     * 任务发送到的队列的名称
     * @var string
     */
    // public $queue = 'remind';

    /**
     * 处理任务的延迟时间
     * @var int
     */
    // public $delay = 5;

    /**
     * 任务可以尝试的最大次数
     * @var integer
     */
    // public $tries = 5;

    /**
     * 任务可以执行的最大秒数 (超时时间)。
     *
     * @var int
     */
    // public $timeout = 120;

    /**
     * 如果模型缺失即删除任务。
     *
     * @var bool
     */
    // public $deleteWhenMissingModels = true;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * 加入通知队列.
     *
     * @param  CreateRemind  $event
     * @return void
     */
    public function handle(CreateRemind $event)
    {
        $delays = strtotime($event->remind->remind_time) - time();

        // $delays = now()->addSeconds($delays);
        // 加上 5s 的误差
        if($delays < 5 ){
            $delays = $delays + 5;
        }
        if($event->remind->status == 0 && $delays >= 0){
            Remind::dispatch($event->remind)->delay($delays)->onQueue('remind');    
        }
    }

    /**
     * 确定监听器是否应加入队列
     * @param CreateRemind $event [description]
     */
    /*public function ShouldQueue(CreateRemind $event){

        return $event->remind->status == 0;
    }*/
}
