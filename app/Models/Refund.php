<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model{

	protected $table = 'refund';

	/**
	 * 所属订单
	 * @return [type] [description]
	 */
	public function order(){

		return $this->belongsTo('App\Models\Orders', 'order_id', 'id');
	}
}