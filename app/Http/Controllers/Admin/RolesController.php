<?php
namespace App\Http\Controllers\Admin;

use Validator;
use App\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Permissions;
use App\Http\Controllers\Admin\BackendController;

class RolesController extends BackendController{

	public function index(Request $request){

		$keywords = $request->input('keywords');

		$list = Role::where('name', 'like', '%'.$keywords.'%')->paginate(10)->appends($request->all());

		return view('admin.roles.index', compact('list','keywords'));
	}

	/**
	 * 创建
	 * @return [type] [description]
	 */
	public function create(){
		$allPermissions = (new Permissions)->bubblingPermissions();

		return view('admin.roles.create', compact('allPermissions'));
	}

	/**
	 * 保存
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function store(Request $request){
		$messages = [
			'required' => '角色名不能为空',
			'unique' => '角色名已存在'
		];
		$validator = Validator::make($request->all(), [
			'name' => 'bail|required|unique:roles'
		], $messages);

		if($validator->fails()){

			return $this->response($validator->errors()->first());
		}

		$name = $request->input('name');
		$desc = $request->input('desc');
		$permissions = $request->input('permission_id');

		$role = Role::create(['name'=>$name, 'desc'=>$desc]);
		if($role){
			if($permissions){
				$role->givePermissionTo($permissions);	
			}

			return $this->response('添加角色成功', 0);
		}
		return $this->response('添加角色失败');
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
		$role = Role::find($id);
		$allPermissions = (new Permissions)->bubblingPermissions();

		return view('admin.roles.edit', compact('allPermissions', 'role'));
	}

	/**
	 * 编辑保存
	 * @param  Request $request [description]
	 * @param  [type]  $id      [description]
	 * @return [type]           [description]
	 */
	public function update(Request $request, $id){
		$messages = [
			'required' => '角色名不能为空',
			'unique' => '角色名已存在'
		];
		$validator = Validator::make($request->all(), [
			'name' => 'bail|sometimes|required|unique:roles,name,'.$id
		], $messages);

		if($validator->fails()){

			return $this->response($validator->errors()->first());
		}
		//是否启用
		$type = $request->input('type');
		if($id == 0 && $type == 'status'){
			$id = $request->input('id');
		}
		if(!$id) return $this->response('编辑角色失败', 1);

		$role = Role::find($id);
		if($request->has('name')){
			$role->name = $request->input('name');
		}
		if($request->has('desc')){
			$role->desc = $request->input('desc');
		}

		$role->status = $request->input('status', 0);

		if($role->save()){
			if($request->has('permission_id')){
				$permissions = $request->input('permission_id');
				$role->syncPermissions($permissions);
			}
			return $this->response('编辑角色成功', 0);
		}

		return $this->response('编辑角色失败', 1);
	}

	/**
	 * 删除
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function destroy(Request $request, $id){
		if(!$id) $id = $request->input('id');

		if(!$id) return $this->response('删除角色失败', 1);

		if(is_array($id)){
			$role = Role::whereIn('id', $id)->get();
		}
		else{
			$role = Role::find($id);	
		}
		
		if(Role::destroy($id)){
			if(is_array($id)){
				foreach($role as $val){
					$val->revokePermissionTo([]);
				}
			}
			else{
				$role->revokePermissionTo([]);
			}
			
			return $this->response('删除角色成功', 0);
		}

		return $this->response('删除角色失败', 1);
	}
}