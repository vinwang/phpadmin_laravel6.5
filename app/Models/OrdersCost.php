<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdersCost extends Model{

	protected $table = 'orders_cost';

	protected $fillable = ['business_cost', 'outsourcing_cost', 'urgent_cost', 'xiaoshou_other_cost', 'equipment_cost', 'trusteeship_cost', 'software_cost', 'jishu_other_cost', 'jishu_remarks', 'order_id'];

	/**
	 * 所属订单
	 * @return [type] [description]
	 */
	public function order(){

		return $this->belongsTo('App\Models\Order', 'order_id', 'id');
	}
}