<?php
namespace App\Http\Controllers\Admin;

use Validator;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Permissions;
use App\Http\Controllers\Admin\BackendController;

class PermissionsController extends BackendController{

	public function index(){

		$list = Permissions::where(['parent_id'=>0])->orderBy('sort', 'asc')->paginate(5);
		foreach($list as $key=>$val){
			$list[$key]->permissions = (new Permissions)->bubblingPermissions($val->id);
		}

		return view('admin.permissions.index', compact('list'));
	}

	/**
	 * 创建
	 * @return [type] [description]
	 */
	public function create(){
		$list = (new Permissions)->bubblingPermissions();

		return view('admin.permissions.create', compact('list'));
	}

	/**
	 * 保存
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function store(Request $request){
		$messages = [
			'required' => '请填写信息',
			'unique' => '数据已存在'
		];
		$validator = Validator::make($request->all(), [
			'name' => 'bail|required|unique:permissions',
			'uri' => 'bail|nullable|unique:permissions'
		], $messages);

		if($validator->fails()){

			return $this->response($validator->errors()->first());
		}

		$parent_id = $request->input('parent_id');
		$name = $request->input('name');
		$uri = $request->input('uri');
		$icon = $request->input('icon');
		$status = $request->input('status', 0);
		$sort = $request->input('sort', 0);

		$data = [
			'parent_id' => $parent_id,
			'name' => $name,
			'uri' => $uri,
			'icon' => $icon,
			'status' => $status,
			'sort' => $sort ?: 0
		];
		$permission = Permissions::create($data);

		return $this->response('添加权限菜单成功', 0);
	}

	/**
	 * 显示
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function show($id){}

	/**
	 * 编辑
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function edit($id){
		$list = (new Permissions)->bubblingPermissions();

		$info = Permissions::find($id);
		
		return view('admin.permissions.edit', compact('list', 'info'));
	}

	/**
	 * 编辑保存
	 * @param  Request $request [description]
	 * @param  [type]  $id      [description]
	 * @return [type]           [description]
	 */
	public function update(Request $request, $id){
		$messages = [
			'required' => '请填写信息',
			'unique' => '数据已存在'
		];
		$validator = Validator::make($request->all(), [
			'name' => 'bail|sometimes|required|unique:permissions,name,'.$id,
			'uri' => 'bail|sometimes|nullable|unique:permissions,uri,'.$id
		], $messages);

		if($validator->fails()){

			return $this->response($validator->errors()->first());
		}

		//是否启用
		$type = $request->input('type');
		if($id == 0 && $type == 'status'){
			$id = $request->input('id');
		}
		if(!$id) return $this->response('编辑权限菜单失败', 1);

		$permission = Permissions::find($id);
		if($request->has('parent_id')){
			$permission->parent_id = $request->input('parent_id');
		}
		if($request->has('name')){
			$permission->name = $request->input('name');
		}
		if($request->has('uri')){
			$permission->uri = $request->input('uri');
		}
		if($request->has('icon')){
			$permission->icon = $request->input('icon');
		}
		if($request->has('sort')){
			$permission->sort = $request->input('sort');
		}

		$permission->status = $request->input('status', 0);

		if($permission->save()){

			return $this->response('编辑权限菜单成功', 0);
		}

		return $this->response('编辑权限菜单失败', 1);
	}

	/**
	 * 删除
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function destroy(Request $request, $id){
		if(!$id) $id = $request->input('id');

		if(Permissions::destroy($id)){
			return $this->response('删除权限菜单成功', 0);
		}

		return $this->response('删除权限菜单失败', 1);
	}
}