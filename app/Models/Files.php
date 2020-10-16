<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Files extends Model{

	protected $table = 'files';

	//获取所有图片的所属合同
	public function contract(){

    	return $this->belongsTo('App\Models\Contract');
    }
}