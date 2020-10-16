<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $table = 'system_log';

    /**
     * 操作日志用户
     * @return [type] [description]
     */
    public function user(){

    	return $this->belongsTo('App\Admin', 'user_id', 'id');
    }
}
