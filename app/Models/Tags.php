<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model{

	protected $table = 'customer_tags';

	public function customers(){

    	return $this->belongsToMany('App\Models\Customer', 'customer_tags_id', 'tags_id', 'customer_id');
    }
}