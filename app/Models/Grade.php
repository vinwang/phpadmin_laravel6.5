<?php
namespace App\Models;

use App\Admin;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model{

	protected $table = 'user_grade';

	/**
	 * 属于该等级的用户
	 * @return [type] [description]
	 */
	public function users(){

		return $this->hasMany('App\Admin', 'grade_id', 'id'); 
	}
}