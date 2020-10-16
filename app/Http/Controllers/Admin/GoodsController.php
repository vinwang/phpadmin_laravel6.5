<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\BackendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Goods;
use App\Admin;

//业务种类
class GoodsController extends BackendController
{
    //业务种类列表
    public function index(Request $request)
    {
        $name = $request->input('name');

        $lists = Goods::where('name','like','%'.$name.'%')->orderBy('id','asc')->paginate(10)->appends($request->all());
        $user = Admin::all();
        foreach ($user as $key => $value) {
            $user[$value['id']] = $value['name'];
        }
        return view('admin.goods.index', compact('lists','user','name'));
    }

    /**
     * 创建
     * @return [type] [description]
     */
    public function create(){

        return view('admin.goods.create');
    }

    /**
     * 保存
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request){
        $messages = [
            'required' => '业务种类名称不能为空',
            'unique' => '业务种类名称已存在'
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|unique:goods'
        ], $messages);

        if($validator->fails()){

            return $this->response($validator->errors()->first());
        }

        $goods = new Goods;
        $goods->name = $request->input('name');
        $goods->status = $request->input('status', 0);
        $goods->description = $request->input('description');
        $id = Auth::guard('admin')->id();
        $goods->uid = $id;
        
        $res = $goods->save();
        if($res){
            return $this->response('添加业务种类成功', 0);
        }else{
            return $this->response('添加业务种类失败', 1);
        }
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
        $list = Goods::find($id);
        return view('admin.goods.edit',['list' => $list]);
    }

    /**
     * 编辑保存
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request,$id){
        $messages = [
            'required' => '业务种类名称不能为空',
            'unique' => '业务种类名称已存在'
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'bail|sometimes|required|unique:goods,name,'.$id
        ], $messages);

        if($validator->fails()){

            return $this->response($validator->errors()->first());
        }

        //是否启用
        $type = $request->input('type');
        if($id == 0 && $type == 'status'){
            $id = $request->input('id');
        }
        if(!$id) return $this->response('编辑业务种类失败', 1);

        $goods = Goods::find($id);
        if($request->input('name') !== null){
            $goods->name = $request->input('name');
        }
        if($request->input('description') !== null){
            $goods->description = $request->input('description');
        }
        $goods->status = $request->input('status', 0);
        
        $uid = Auth::guard('admin')->id();
        $goods->uid = $uid;
        
        $res = $goods->save();
        if($res){
            return $this->response('编辑业务种类成功', 0);
        }else{
            return $this->response('编辑业务种类失败', 1);
        }
    }

    /**
     * 删除
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy(Request $request,$id){
        if(!$id) $id = $request->input('id');

        if(Goods::destroy($id)){
            return $this->response('删除业务种类成功', 0);
        }

        return $this->response('删除业务种类失败', 1);
    }
}
