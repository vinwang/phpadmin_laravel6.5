<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model{

	protected $table = 'contracts';

	//获取此合同下的所有图片
	public function files(){

    	return $this->hasMany('App\Models\Files');
    }

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
}