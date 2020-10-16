<?php
namespace App\Http\Controllers\Admin;

use Validator;
use App\Admin;
use App\Models\Tags;
use App\Models\Source;
use App\Models\Roles;
use App\Models\Customer;
use App\Models\ReceiveAssignRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BackendController; 
use Illuminate\Database\Eloquent\Builder;

//客户公海
class SeaController extends BackendController
{
    public function index(Request $request)
    {   
        $source = [];
        $user = [];
        $name = $request->input('name'); 
        
        $data = Customer::where('company','like','%'.$name.'%')
            ->where('status', 0)
            ->orderBy('created_at','desc')->paginate(10)->appends($request->all());

        $users = Admin::all();
        foreach($users as $val){
            $user[$val['id']] = $val['name'];
        }
        $sources = Source::all();
        foreach($sources as $val){
            $source[$val['id']] = $val['name'];
        }
        $username = auth('admin')->user();
        return view('admin.sea.index', compact('data', 'user', 'source','username', 'name'));
    }

    /*客户分配*/
    public function edit($id)
    { 
        $data = Customer::find($id); 
        $users = Admin::where('id','<>',1)->where('id','<>',auth('admin')->user()->id)->whereHas('roles', function(Builder $query){$query->where('id',2);})->get();

        return view('admin.sea.edit', compact('data', 'users'));
    }

    /**
     * 编辑保存
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $id){
        $messages = [
            'uid.required' => '分配用户不能为空'
        ];
        $validator = Validator::make($request->all(), [
            'uid' => 'bail|required'
        ], $messages);

        if($validator->fails()){

            return $this->response($validator->errors()->first());
        }
       
        $customer =  Customer::find($id); 
        $customer->uid = $request->input('uid');
        $customer->status = 1;
        
        if($customer->save()){
            //分配客户记录
            $record = new ReceiveAssignRecord;
            $record->type = 2;
            $record->customer_id = $customer->id;
            $record->person_id = $customer->uid;
            $record->user_id = auth('admin')->user()->id;
            $record->save();
            //客户分配提醒
            $this->createRemind('已分配给您', '', 'Customer', $customer->uid, $customer->id);

            return $this->response('分配客户成功', '0');
        }
        
        return $this->response('分配客户失败'); 
    }

    /**
     * 领取
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request){ 
        
      $id = $request->input('id');
      $customer =  Customer::find($id);
      $customer_id = $customer->uid; 
      $customer->uid = auth('admin')->user()->id;
      $customer->status = 1; 
      
        if($customer->save()){  
            //領取客户记录
            $record = new ReceiveAssignRecord;
            $record->type = 1;
            $record->customer_id = $customer->id;
            $record->person_id = $customer_id;
            $record->user_id = auth('admin')->user()->id;
            $record->save();
            return $this->response('领取客户成功', '0');
        }else{
            return $this->response('领取客户失败');
        } 
       
    }
}
