<?php

namespace App\Console;

use App\Models\Remind;
use App\Models\Sysconf;
use App\Models\Orders;
use App\Models\Customer;
use App\Models\ReceiveAssignRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $sysconfig = Cache::rememberForever('sysconfig', function(){

            $tmpConfig = Sysconf::all()->toArray();
            return Arr::pluck($tmpConfig, 'value', 'name');
        });

        //过期提醒处置         
        $schedule->call(function(){
            $reminds = Remind::where('status', 0)->get();
            foreach($reminds as $remind){
                //已过期
                if(time() > $remind->remind_time){
                    $remind->status = 2;
                    $remind->save();
                }
            }
        })->daily();

        //回收公海处置
        $schedule->call(function(){
            $datetime = "+".$sysconfig['cycle']." days";
            $record_assign = [];
            //查询没有订单且已分配的客户
            $customers = Customer::whereHas('orders', function($query){},'=',0)->where('status',1)->get();
            foreach ($customers as $key => $value) {
                //查出没有订单且已分配的添加时间
                $createtime = strtotime($value['created_at']);
                $lasttime = strtotime($datetime,$createtime);
                $time = time();
                if($lasttime < $time){
                    $value->status = 0;
                    if($value->save()){
                        //退回客户记录
                        $record_assign[] = [
                            'type' => 3,
                            'customer_id' => $value->id,
                            'person_id' => $value->uid,
                            'user_id' => 0,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                    }
                }
            }
            if($record_assign){
                $record = new ReceiveAssignRecord;
                $record->insert($record_assign);
            }
        })->daily();

        $schedule->exec('sh /home/wwwroot/ly_crm/backup.sh')->weeklyOn(5, '23:59');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
