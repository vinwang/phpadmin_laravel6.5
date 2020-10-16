<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodProvincesRecord extends Model{

	protected $table = 'good_provinces_record';

	/**
	 * 操作的用户
	 * @return [type] [description]
	 */
	public function user(){

		return $this->belongsTo('App\Admin', 'user_id', 'id');
	}

}