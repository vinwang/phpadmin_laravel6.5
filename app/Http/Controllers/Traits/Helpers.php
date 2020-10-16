<?php

namespace App\Http\Controllers\Traits;

use App\Models\Remind;
use App\Models\Permissions;
use App\Events\SystemLog;
use App\Events\CreateRemind;

trait Helpers{
	
	/**
     * JSON响应
     * @param  string  $content [description]
     * @param  integer $code    [0为正确，其他为错误]
     * @param  array   $data    [description]
     * @return [type]           [description]
     */
    public function response($msg = '', $code = 1, $data = []){

        $data['uri'] = isset($data['uri']) && $data['uri'] ? $data['uri'] : '';

        //操作成功,记录操作日志
        if($code == 0){
            $route = request()->route()->getName();
            $permission_name = Permissions::where('uri', $route)->value('name');
            if($permission_name){
                $logMsg = $msg ?: $permission_name; 
                event(new SystemLog($logMsg));
            }
        }
        return response()->json(['msg'=>$msg, 'code'=>$code, 'data'=>$data]);
    }

    public function amount($money){
        $all = preg_replace('/(?<=[0-9])(?=(?:[0-9]{3})+(?![0-9]))/', ',', $money);
        return $all;
    }

    /**
     * 提醒事件触发
     * @param  string $content     [提醒内容]
     * @param  string $remind_time [提醒时间,格式：2019-12-20 18:00:00]
     * @param  string $model       [提醒类型,Remind,Customer,Order]
     * @param  array  $user_id     [提醒对象]
     * @param  array  $customer    [分配客户时存在]
     * @return [type]              [description]
     */
    public function createRemind($content = '', $remind_time = '', $model = '', $user_id = [], $customer = []){

    	$remind = new Remind;
        $remind->content = $content;
        $remind->remind_time = $remind_time ?: date('Y-m-d H:i:s');
        $remind->admin_id = auth('admin')->user()->id;
        $remind->model = $model;
        $remind->save();

        $remind->users()->sync($user_id);
        if($customer){
        	$remind->customers()->sync($customer);	
        }

        event(new CreateRemind($remind));
    }

    /**
     * 随机字符
     * @param  [type] $min [description]
     * @param  [type] $max [description]
     * @return [type]      [description]
     */
    public function cryptoRandSecure($min, $max){
        $range = $max - $min;
        if($range < 1) return $min;
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);

        return $min + $rnd;
    }
}