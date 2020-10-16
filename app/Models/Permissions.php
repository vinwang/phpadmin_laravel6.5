<?php

namespace App\Models;

use Spatie\Permission\Models\Permission;

class Permissions extends Permission{

	/**
	 * 冒泡获取子权限菜单
	 * @param  integer $id        [description]
	 * @param  array   $condition [description]
	 * @return [type]             [description]
	 */
	public function bubblingPermissions($id = 0, $condition = []){
		$where = [];
		$where['parent_id'] = $id;
		if($condition){
			$where = array_merge($where, $condition);
		}

		$list = parent::where($where)->orderBy('sort', 'asc')->get();

		foreach($list as $key=>$val){
			$list[$key]->permissions = $this->bubblingPermissions($val->id, $condition);
		}

		return $list;
	}
}