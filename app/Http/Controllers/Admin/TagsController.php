<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\BackendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Tags;

//客户标签
class TagsController extends BackendController
{
    //客户标签列表
    public function index(Request $request)
    {
        $tagname = $request->input('tagname');
        $where = [];
        if ($tagname) {
            $where[] = ['tagname','like','%'.$tagname.'%'];
        }
        $lists = Tags::where($where)->orderBy('id','desc')->paginate(10)->appends($request->all());
        return view('admin.tags.index', compact('lists', 'tagname'));
    }

    /**
     * 创建
     * @return [type] [description]
     */
    public function create(){
        return view('admin.tags.create');
    }

    /**
     * 保存
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request){
        $messages = [
            'required' => '标签名不能为空',
            'unique' => '标签名已存在'
        ];
        $validator = Validator::make($request->all(), [
            'tagname' => 'bail|required|unique:customer_tags'
        ], $messages);

        if($validator->fails()){

            return $this->response($validator->errors()->first());
        }

        $tag = new Tags;
        $tag->tagname = $request->input('tagname');
        $id = Auth::guard('admin')->id();
        $tag->uid = $id;
        
        $res = $tag->save();
        if($res){
            return $this->response('添加客户标签成功', 0);
        }else{
            return $this->response('添加客户标签失败', 1);
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
        $list = Tags::find($id);
        return view('admin.tags.edit',['list' => $list]);
    }

    /**
     * 编辑保存
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request,$id){
        $messages = [
            'required' => '标签名不能为空',
            'unique' => '标签名已存在'
        ];
        $validator = Validator::make($request->all(), [
            'tagname' => 'bail|required|unique:customer_tags,tagname,'.$id
        ], $messages);

        if($validator->fails()){

            return $this->response($validator->errors()->first());
        }

        $tag = Tags::find($id);
        $tag->tagname = $request->input('tagname');
        $uid = Auth::guard('admin')->id();
        $tag->uid = $uid;
        
        $res = $tag->save();
        if($res){
            return $this->response('编辑客户标签成功', 0);
        }else{
            return $this->response('编辑客户标签失败', 1);
        }
    }

    /**
     * 删除
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy(Request $request,$id){
        if(!$id) $id = $request->input('id');

        if(Tags::destroy($id)){
            return $this->response('删除客户标签成功', 0);
        }

        return $this->response('删除客户标签失败', 1);
    }
}
