<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\BackendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Fup;
use App\Models\Customer;
use App\Admin;

//跟进记录
class FupController extends BackendController
{
    //跟进记录列表
    public function index(Request $request)
    { 
        $id = $request->input('id');
        $where = [];
        if ($id) {
            $where['customer_id'] = $id;
        }
        $lists = Fup::where($where)->orderBy('id','desc')->paginate(10)->appends($request->all());
        $customer = Customer::find($id);
        $user = Admin::find($customer['uid']);
        return view('admin.fup.index',compact('lists','id','user','customer'));
    }
   
    /**
     * 保存
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request){
        $messages = [
            'required' => '跟进内容不能为空',
            'unique' => '跟进内容已存在'
        ];
        $validator = Validator::make($request->all(), [
            'fupcontent' => 'bail|required|unique:customer_fup'
        ], $messages);

        if($validator->fails()){

            return $this->response($validator->errors()->first());
        }

        $fup = new Fup;
        $fup->fupcontent = $request->input('fupcontent');
        $fup->customer_id = $request->input('customer_id'); 
        $id = Auth::guard('admin')->id();
        // $tag->uid = $id;
        
        $res = $fup->save();
        if($res){
            return $this->response('添加跟进记录成功', 0);
        }else{
            return $this->response('添加跟进记录失败', 1);
        }
    } 
   
}
