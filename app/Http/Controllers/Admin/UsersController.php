<?php
namespace App\Http\Controllers\Admin;

use Validator;
use App\Admin;
use App\Models\Grade;
use Illuminate\Http\Request;
use App\Models\Permissions;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Admin\BackendController;

class UsersController extends BackendController{

	public function index(Request $request){
		$keywords = $request->input('keywords');

		$users = Admin::where('name', 'like', '%'.$keywords.'%')
			->where('status', 1)
			->where(function($query){
                $user = auth('admin')->user();
                if(!$user->hasRole(1)/* && !$user->hasPermissionTo(46)*/){
                    $query->whereHas('roles', function($rquery) use($user){
                    	$role = $user->roles->first();
                    	$rquery->where('id', $role->id);
                    });
                }
            })
			->paginate(10)->appends($request->all());

		return view('admin.users.index', compact('users','keywords'));
	}

	/**
	 * 创建
	 * @return [type] [description]
	 */
	public function create(){
		$roles = Role::where('status', 1)->get();
		$allPermissions = (new Permissions)->bubblingPermissions();
		$grade = Grade::all();

		return view('admin.users.create', compact('allPermissions', 'roles', 'grade'));
	}

	/**
	 * 保存
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function store(Request $request){
		$messages = [
			'required' => '数据不能为空',
			'unique' => '数据已存在',
			'email' => '邮箱格式不正确',
			'regex' => '手机号格式不正确'
		];
		$validator = Validator::make($request->all(), [
			'name' => 'bail|required|unique:users',
			'email' => 'bail|nullable|email|unique:users',
			'password' => 'bail|required|min:6',
			'phone' => 'bail|nullable|regex:/^1[3456789]\d{9}$/|unique:users'
		], $messages);

		if($validator->fails()){

			return $this->response($validator->errors()->first());
		}

		$password = $request->input('password');
		if($password != $request->input('password2')){

			return $this->response('密码不一致', 1);
		}
		$user = new Admin;
		$user->name = $request->input('name');
		$user->password = bcrypt($request->input('password'));
		$user->email = $request->input('email');
		$user->nickname = $request->input('nickname');
		$user->phone = $request->input('phone');
		$user->grade_id = $request->input('grade_id');

		if($user->save()){
			$user->assignRole($request->input('role'));
			return $this->response('添加用户成功', 0);
		}
		return $this->response('添加用户失败', 1);
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

		$user = Admin::find($id);
		$roles = Role::where('status', 1)->get();
		$allPermissions = (new Permissions)->bubblingPermissions();
		$grade = Grade::all();

		$admin = auth('admin')->user();
		$userGrade = 0;
		if($admin->grade){
			$userGrade = $admin->grade->id;
		}

		return view('admin.users.edit', compact('user', 'allPermissions', 'roles', 'grade', 'userGrade'));
	}

	/**
	 * 编辑保存
	 * @param  Request $request [description]
	 * @param  [type]  $id      [description]
	 * @return [type]           [description]
	 */
	public function update(Request $request, $id){
		$messages = [
			'required' => '数据不能为空',
			'unique' => '数据已存在',
			'email' => '邮箱格式不正确',
			'regex' => '手机号格式不正确'
		];
		$validator = Validator::make($request->all(), [
			'name' => 'bail|required|unique:users,name,'.$id,
			'email' => 'bail|nullable|email|unique:users,email,'.$id,
			'password' => 'bail|nullable|min:6',
			'phone' => 'bail|nullable|regex:/^1[3456789]\d{9}$/|unique:users,phone,'.$id
		], $messages);

		if($validator->fails()){

			return $this->response($validator->errors()->first());
		}
		if($request->input('password') != $request->input('password2')){
			return $this->response('密码不一致', 1);
		}

		$user = Admin::find($id);
		$user->name = $request->input('name');
		if($request->input('password')){
			$user->password = bcrypt($request->input('password'));
		}
		$user->email = $request->input('email');
		$user->nickname = $request->input('nickname');
		$user->phone = $request->input('phone');
		if($request->has('grade_id')){
			$user->grade_id = $request->input('grade_id');	
		}

		if($user->save()){
			if($request->has('permission_id')){
				$permissions = $request->input('permission_id');
				$user->syncPermissions($permissions);
			}
			if($request->has('role')){
				$user->syncRoles($request->input('role'));
			}
			
			return $this->response('编辑用户成功', 0);
		}

		return $this->response('编辑用户失败', 1);
	}

	/**
	 * 删除
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function destroy(Request $request, $id){
		if(!$id) $id = $request->input('id');

		if(!$id) return $this->response('删除用户失败', 1);

		if(is_array($id)){
			$user = Admin::whereIn('id', $id)->get();
		}
		else{
			$user = Admin::find($id);	
		}
		
		if(Admin::destroy($id)){
			if(is_array($id)){
				foreach($user as $val){
					$val->roles()->detach();
				}
			}
			else{
				$user->roles()->detach();
			}
			
			return $this->response('删除用户成功', 0);
		}

		return $this->response('删除用户失败', 1);
	}
}