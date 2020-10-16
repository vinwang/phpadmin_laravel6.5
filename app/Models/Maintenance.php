<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model{

	protected $table = 'orders_maintenance';
    protected $fillable = ['customer_id', 'order_id', 'device_msg', 'user_id'];

    /**
     * 所属客户
     * @return [type] [description]
     */
    public function customer(){

        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }

    /**
     * 所属订单
     * @return [type] [description]
     */
    public function order(){

        return $this->belongsTo('App\Models\Orders', 'order_id', 'id');
    }

    /**
     * 所属用户
     * @return [type] [description]
     */
    public function user(){

        return $this->belongsTo('App\Admin', 'user_id', 'id');
    }
}