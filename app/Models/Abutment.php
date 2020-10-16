<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abutment extends Model{

	protected $table = 'orders_abutment';

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
     * 所属业务种类
     * @return [type] [description]
     */
    public function good(){

        return $this->belongsTo('App\Models\Goods', 'good_id', 'id');
    }

    /**
     * 所属节点
     * @return [type] [description]
     */
    public function province(){

        return $this->belongsTo('App\Models\Provinces', 'node_id', 'id');
    }

    /**
     * 所属用户
     * @return [type] [description]
     */
    public function user(){

        return $this->belongsTo('App\Admin', 'user_id', 'id');
    }
}