<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model{

	protected $table = 'customer_source';

	/**
	 * 客户
	 * @return [type] [description]
	 */
	public function customers(){

		return $this->hasMany('App\Models\Customer', 'source_id', 'id');
	}
}