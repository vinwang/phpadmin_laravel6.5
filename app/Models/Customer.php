<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $table = 'customer';

    /**
     * 客户来源
     * @return [type] [description]
     */
    public function source(){

    	return $this->belongsTo('App\Models\Source', 'source_id', 'id');
    }

    /**
     * 客户标签
     * @return [type] [description]
     */
    public function tags(){

    	return $this->belongsToMany('App\Models\Tags', 'customer_tags_id','customer_id', 'tags_id');
    }

    /**
     * 提醒
     * @return [type] [description]
     */
    public function remind(){

        return $this->belongsToMany('App\Models\Remind', 'customer_remind', 'customer_id', 'remind_id');
    }

    /**
     * 所归属的用户
     * @return [type] [description]
     */
    public function user(){

        return $this->belongsTo('App\Admin', 'uid', 'id');
    }

    /**
     * 订单
     * @return [type] [description]
     */
    public function orders(){

        return $this->hasMany('App\Models\Orders', 'customer_id', 'id');
    }

    /**
     * 回款订单
     * @return [type] [description]
     */
    public function receivables(){

        return $this->hasManyThrough('App\Models\Receivables', 'App\Models\Orders', 'customer_id', 'order_id', 'id', 'id');
    }

    /**
     * 跟进记录
     * @return [type] [description]
     */
    public function fup(){

        return $this->belongsTo('App\Models\Fup', 'id', 'customer_id');
    }
}
