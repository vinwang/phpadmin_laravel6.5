<?php
namespace App\Http\Controllers\Admin;

use Validator;
use App\Admin;
use Illuminate\Http\Request;
use App\Models\Grade;
use App\Http\Controllers\Admin\BackendController;

class GradeController extends BackendController{

	public function index(Request $request){
		$keywords = $request->input('keywords');

		$grade = Grade::where('name', 'like', '%'.$keywords.'%')->paginate(15)->appends($request->all());

		return view('admin.grade.index', compact('grade','keywords'));
	}

	/**
	 * 创建
	 * @return [type] [description]
	 */
	public function create(){

		return view('admin.grade.create');
	}

	/**
	 * 保存
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function store(Request $request){
		$messages = [
			'required' => '数据不能为空',
			'unique' => '数据已存在'
		];
		$validator = Validator::make($request->all(), [
			'name' => 'bail|required|unique:user_grade'
		], $messages);

		if($validator->fails()){

			return $this->response($validator->errors()->first());
		}

		
		$grade = new Grade;
		$grade->name = $request->input('name');

		if($grade->save()){

			return $this->response('添加用户等级成功', 0);
		}
		return $this->response('添加用户等级失败', 1);
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

		$grade = Grade::find($id);

		return view('admin.grade.edit', compact('grade'));
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
			'unique' => '数据已存在'
		];
		$validator = Validator::make($request->all(), [
			'name' => 'bail|required|unique:user_grade,name,'.$id,
		], $messages);

		if($validator->fails()){

			return $this->response($validator->errors()->first());
		}

		$grade = Grade::find($id);
		$grade->name = $request->input('name');

		if($grade->save()){

			return $this->response('编辑用户等级成功', 0);
		}

		return $this->response('编辑用户等级失败', 1);
	}

	/**
	 * 删除
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function destroy(Request $request, $id){
		if(!$id) $id = $request->input('id');

		if(!$id) return $this->response('删除用户等级失败', 1);
		
		if(Grade::destroy($id)){
			
			return $this->response('删除用户等级成功', 0);
		}

		return $this->response('删除用户等级失败', 1);
	}
}