<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\BackendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Bank;

//客户标签
class BankController extends BackendController
{
    //客户银行信息列表
    public function index(Request $request)
    {
        $id = $request->input('id');
        $where = [];
        if ($id) {
            $where['customer_id'] = $id;
        }
        $list = Bank::where($where)->orderBy('id','desc')->paginate(10)->appends($request->all());
        return view('admin.bank.index',compact('list','id'));
    }

    /**
     * 创建
     * @return [type] [description]
     */
    public function create(Request $request){ 
        $id = $request->input('id'); 
        return view('admin.bank.create',compact('id'));
    }

    /**
     * 保存
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request){
        $messages = [
            'required' => '开户行账号不能为空',
            'unique' => '开户行账号已存在'
        ];
        $validator = Validator::make($request->all(), [
            'banknumber' => 'bail|required|unique:customer_bank'
        ], $messages);

        if($validator->fails()){

            return $this->response($validator->errors()->first());
        }

        $bank = new Bank;
        $bank->bankname = $request->input('bankname');
        $bank->banknumber = $request->input('banknumber');
        $bank->bankadd = $request->input('bankadd');
        $bank->companycode = $request->input('companycode');
        $bank->customer_id = $request->input('id');

        $res = $bank->save();
        if($res){
            return $this->response('添加银行成功', 0);
        }else{
            return $this->response('添加银行失败', 1);
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
        $list = Bank::find($id);
        return view('admin.bank.edit',['list' => $list]);
    }

    /**
     * 编辑保存
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request,$id){
        $messages = [
            'required' => '开户行账号不能为空',
            'unique' => '开户行账号已存在'
        ];
        $validator = Validator::make($request->all(), [
            'banknumber' => 'bail|required|unique:customer_bank,banknumber,'.$id
        ], $messages);

        if($validator->fails()){

            return $this->response($validator->errors()->first());
        }

        $bank = Bank::find($id);
        $bank->bankname = $request->input('bankname');
        $bank->banknumber = $request->input('banknumber');
        $bank->bankadd = $request->input('bankadd');
        $bank->companycode = $request->input('companycode'); 
        
        $res = $bank->save();
        if($res){
            return $this->response('编辑银行成功', 0);
        }else{
            return $this->response('编辑银行失败', 1);
        }
    }

    /**
     * 删除
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy(Request $request,$id){
        if(!$id) $id = $request->input('id');

        if(Bank::destroy($id)){
            return $this->response('删除银行成功', 0);
        }

        return $this->response('删除银行失败', 1);
    }
}
