<?php

namespace App\Jobs;

use Exception;
use Notification;
use App\Models\Remind as RemindModel;
use Illuminate\Bus\Queueable;
use App\Notifications\InvoiceRemind;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Remind implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 任务可以尝试的最大次数
     * @var integer
     */
    public $tries = 5;

    /**
     * 应该处理任务的队列连接
     * @var string
     */
    // public $connection = 'redis';
    // 
    
    /**
     * 任务可以执行的最大秒数 (超时时间)。
     *
     * @var int
     */
    public $timeout = 120;

    public $remind;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RemindModel $remind)
    {
        $this->remind = $remind;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Notification::send($this->remind->users, new InvoiceRemind($this->remind));
    }

    /**
     * 任务失败的处理过程
     * @param  Exception $e [description]
     * @return [type]       [description]
     */
    public function failed(Exception $e){
        // 给用户发送任务失败的通知，等等……
        // 
    }
}
