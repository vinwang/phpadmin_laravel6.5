<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderActionRecord extends Model{

	protected $table = 'order_action_record';

	protected $fillable = ['order_id', 'user_id', 'content'];

	/**
	 * 流转的用户
	 * @return [type] [description]
	 */
	public function users(){

		return $this->belongsToMany('App\Admin', 'order_record_user', 'record_id', 'user_id');
	}

	/**
	 * 操作的用户
	 * @return [type] [description]
	 */
	public function user(){

		return $this->belongsTo('App\Admin', 'user_id', 'id');
	}
}