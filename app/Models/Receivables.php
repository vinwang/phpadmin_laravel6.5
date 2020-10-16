<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receivables extends Model{

	protected $table = 'receivables';

	protected $fillable = ['money', 'order_id', 'back_time'];

	/**
	 * 所属订单
	 * @return [type] [description]
	 */
	public function order(){

		return $this->belongsTo('App\Models\Orders', 'order_id', 'id');
	}
}
