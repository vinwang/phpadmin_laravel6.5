<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model{

	protected $table = 'orders';

   /**
     * 订单分配记录
     * @return [type] [description]
     */
    public function distribute(){

    	return $this->hasMany('App\Models\distribute', 'id', 'order_id');
    }

    /**
     * 订单分配角色
     * @return [type] [description]
     */
    public function orderActionRecord(){

    	return $this->hasMany('App\Models\OrderActionRecord', 'order_id', 'id');
    }


    //获取此订单下的所有业务种类及分布节点
    public function goodProvinces(){

        return $this->hasMany('App\Models\GoodProvinces', 'order_id', 'id');
    }

    /**
     * 所属客户
     * @return [type] [description]
     */
    public function customer(){

        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }

    /**
     * 订单归属人
     */
    public function user(){

        return $this->belongsTo('App\Admin', 'user_id', 'id');
    }

    /**
     * 回款
     * @return [type] [description]
     */
    public function receivables(){

        return $this->hasMany('App\Models\Receivables', 'order_id', 'id');
    }

    /**
     * 成本
     * @return [type] [description]
     */
    public function cost(){

        return $this->hasOne('App\Models\OrdersCost', 'order_id', 'id');
    }

    /**
     * 合同
     * @return [type] [description]
     */
    public function contracts(){

        return $this->hasOne('App\Models\Contract', 'order_id', 'id');
    }

    /**
     * 对接订单
     * @return [type] [description]
     */
    public function abutment(){

        return $this->hasOne('App\Models\Abutment', 'order_id', 'id');
    }

    /**
     * 维保订单
     * @return [type] [description]
     */
    public function maintenance(){

        return $this->hasOne('App\Models\Maintenance', 'order_id', 'id');
    }

    /**
     * 退款
     * @return [type] [description]
     */
    public function refund(){

        return $this->hasMany('App\Models\Refund', 'order_id', 'id');
    }
}