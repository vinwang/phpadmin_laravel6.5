<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BackendController;
use App\Models\Source;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

//客户来源
class SourceController extends BackendController
{
    public function index(Request $request)
    {
        $name = '';
        $name = $request->input('name');
        $where[] = ['name','like','%'.$name.'%'];

        $lists = Source::where($where)->orderBy('id','desc')->paginate(10)->appends($request->all());
        return view('admin.source.index', compact('lists', 'name'));
    }

    public function create()
    {
    	return view('admin.source.create');
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => '请填写信息',
            'unique' => '数据已存在',
            'max' => '请输入在20字以内',
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'bail|max:20|required|unique:customer_source',
        ], $messages);

        if($validator->fails()){
            return $this->response($validator->errors()->first());
        }

        $sou = new Source();
        $sou -> name = $request->input('name');

        $res = $sou->save();
        if($res){
            return $this->response('添加客户来源成功', 0);
        }else{
            return $this->response('添加客户来源失败', 1);
        }
    }

    public function edit($id)
    {
        $list = Source::find($id);
    	return view('admin.source.edit', compact('list'));
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'required' => '请填写信息',
            'unique' => '数据已存在或者未更改',
            'max' => '请输入在20字以内',
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'bail|max:20|required|unique:customer_source',
        ], $messages);

        if($validator->fails()){
            return $this->response($validator->errors()->first());
        }

        $sou = Source::find($id);
        $sou -> name = $request->input('name');

        $res = $sou->save();
        if($res){
            return $this->response('编辑客户来源成功', 0);
        }else{
            return $this->response('编辑客户来源失败', 1);
        }
    }

    public function destroy(Request $request,$id)
    {
        if(!$id) $id = $request->input('id');

        if(Source::destroy($id)){
            return $this->response('删除客户来源成功', 0);
        }
        return $this->response('删除客户来源失败', 1);
    }
}
