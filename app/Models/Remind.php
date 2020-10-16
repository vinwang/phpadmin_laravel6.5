<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 提醒
 */
class Remind extends Model{

	protected $table = 'remind';

	/**
	 * 提醒的用户
	 * @return [type] [description]
	 */
	public function users(){

		return $this->belongsToMany('App\Admin', 'user_remind', 'remind_id', 'user_id');
	}

	/**
	 * 本提醒对应的客户
	 * @return [type] [description]
	 */
	public function customers(){
		return $this->belongsToMany('App\Models\Customer', 'customer_remind', 'remind_id', 'customer_id');
	}
}
