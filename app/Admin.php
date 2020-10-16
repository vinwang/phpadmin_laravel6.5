<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //权限守卫
    protected $guard_name = 'web';

    /**
     * 提醒
     * @return [type] [description]
     */
    public function remind(){

        return $this->belongsToMany('App\Models\Remind', 'user_remind', 'user_id', 'remind_id');
    }

    /**
     * [所建立的客户]
     * @return [type] [description]
     */
    public function customers(){

        return $this->hasMany('App\Models\Customer', 'uid', 'id');
    }

    /**
     * 用户所属等级
     * @return [type] [description]
     */
    public function grade(){

        return $this->belongsTo('App\Models\Grade', 'grade_id', 'id');
    }

    /**
     * 订单操作记录
     * @return [type] [description]
     */
    public function records(){

        return $this->belongsToMany('App\Models\OrderActionRecord', 'order_record_user', 'user_id', 'record_id');
    }

    /**
     * 操作日志
     * @return [type] [description]
     */
    public function logs(){

        return $this->hasMany('App\Models\SystemLog', 'user_id', 'id');
    }
}
