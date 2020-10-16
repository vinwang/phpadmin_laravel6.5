<?php
namespace App\Http\Controllers\Admin;

use App\Models\Fup;
use App\Models\Orders;
use App\Models\Receivables;
use App\Models\Remind;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\BackendController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Source;
use App\Models\Tags;
use App\Models\ReceiveAssignRecord;
use App\Admin;

//客户管理
class CustomerController extends BackendController
{
    public function index(Request $request)
    {
        $name = $request->input('name');
        $type = $request->input('hktype');
        $user_id = $request->input('user_id');
        $where = [];
        if($user_id){
            $where['uid'] = $user_id;
        }
        $source = [];
        $date = $request->input('date', '');
        $data = Customer::where('company','like','%'.$name.'%')->where($where);
        if($type == 1){
            $data = $data->has('receivables');
        }
        if($request->has('analysidate')){
            $analysidate = $request->input('analysidate');
            $year = date('Y', strtotime($analysidate));
            $month = date('m', strtotime($analysidate));
            $data = $data->whereYear('created_at', $year)->whereMonth('created_at', $month);
        }
        $data = $data->where('status', 1)
            ->where(function($query){
                $user = auth('admin')->user();
                if(!$user->hasRole(1) && !$user->hasPermissionTo(46)){

                    $query->where('uid', $user->id);
                }
            })
            ->orderBy('id','desc');
        if ($date != '') {
            $data = $data->whereBetween('created_at', [date('Y-m-01', strtotime($date)),date('Y-m-t', strtotime($date))]);
        }

        $start = date('Y-m-01 00:00:00',strtotime($date));
        $end = date('Y-m-t 23:59:59',strtotime($date));

        $analysis_method = $request->input('method');
        if ($analysis_method!='') {
            $id = [0];
            $data = Customer::where('id', $id);
            if ($analysis_method==1) {
                $data = Customer::whereBetween('created_at', [$start, $end])
                    ->where(function($query){
                        $user = auth('admin')->user();
                        if(!$user->hasRole(1) && !$user->hasPermissionTo(46)){
                            $query->where('uid', $user->id);
                        }
                    });
            }
            if ($analysis_method==2) {
                $mount_visit_customer = $request->input('mount_visit_customer');
                if(!empty($mount_visit_customer)) {
                    $data = Customer::whereIn('id', $mount_visit_customer);
                }
            }
            if ($analysis_method==3) {
                $mount_with_customer = $request->input('mount_with_customer');
                if(!empty($mount_with_customer)) {
                    $data = Customer::whereIn('id',$mount_with_customer);
                }
            }
            if ($analysis_method==4) {
                $mount_with_customer = $request->input('mount_with_customer');
                $data = Customer::whereBetween('created_at', ['0000.00.00 00:00:00', $end]);
                $data = $data -> where(function($query){
                    $user = auth('admin')->user();
                    if(!$user->hasRole(1) && !$user->hasPermissionTo(46)){
                        $query->where('uid', $user->id);
                    }
                });
                if(!empty($mount_with_customer)) {
                    $data = $data->whereNotIn('id',$mount_with_customer);
                }
            }
            if ($analysis_method==5) {
                $data = Orders::whereBetween('created_at', [$start, $end])->where('process', 18)
                    ->where(function($query){
                        $user = auth('admin')->user();
                        if(!$user->hasRole(1) && !$user->hasPermissionTo(46)){
                            $query->where('user_id', $user->id);
                        }
                    });
            }
        }

        $data = $data->paginate(10)->appends($request->all());

        $users = Admin::whereHas('roles', function($query){
                    $query->where('id', 2);
                })->get();

        return view('admin.customer.index', compact('data', 'users', 'name', 'user_id'));
    }


    /**
     * 显示
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function show($id){
        $data = Customer::find($id);
        $source = Source::find($data['source_id']);
        $tags = $data->tags;
        return view('admin.customer.show', compact('id','data','source','tags'));
    }

    /*客户添加*/
    public function create(Request $request)
    {
        $tags = [];
        $res = Source::all();
        $tag = Tags::all();
        foreach($tag as $key=>$val){
            $tags[$key]['name'] = $val->tagname;
            $tags[$key]['value'] = $val->id;
        }
        return view('admin.customer.create',['res'=>$res,'tags'=>$tags]);
    }


    /**
     * 保存
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request){

        $messages = [
            'company.required' => '公司名称不能为空',
            'company.unique' => '公司名称已存在',
            'name.required' => '联系人姓名不能为空',
            'phone.required' => '联系人手机号码不能为空',
            'source_id.required' => '客户来源不能为空',
            'phone.regex' => '手机号格式不正确'

        ];
        $validator = Validator::make($request->all(), [
            'company' => 'bail|required|unique:customer',
            'name' => 'bail|required',
            'phone' => 'bail|required|regex:/^1[3456789]\d{9}$/'
        ], $messages);

        if($validator->fails()){

            return $this->response($validator->errors()->first());
        }

        if($request->input('tel')){
            if(!preg_match('/^(\\+\\d{2}-)?0\\d{2,3}-\\d{7,8}$/', $request->input('tel'))){
                return $this->response('公司座机格式错误', 1);
            }
        }

        $customer = new Customer;
        $tags = new Tags;
        $customer->company = $request->input('company');
        $customer->address = $request->input('address');
        $customer->source_id = $request->input('source_id');
        $tags->tags_id = $request->input('tags_id');
        $customer->name = $request->input('name');
        $customer->sex = $request->input('sex', 0);
        $customer->phone = $request->input('phone');
        $customer->tel = $request->input('tel');
        $customer->emil = $request->input('emil');
        $customer->job = $request->input('job');
        $customer->username = $request->input('username');
        $customer->password = $request->input('password');
        $customer->status = $request->input('status');
        $customer->remarks = $request->input('remarks');
        $id = auth('admin')->user()->id;
        $customer->uid = $id;

        if($customer->save()){

            $tags_id = explode(',', $tags->tags_id);
            if($tags_id[0] != ''){
                $customer->tags()->attach($tags_id);
            }

            //新增客户记录
            $record = new ReceiveAssignRecord;
            $record->customer_id = $customer->id;
            $record->person_id = $customer->uid;
            $record->user_id = $customer->uid;
            $record->save();

            $uri = '';
            $tabTitle = '';
            if($customer->status == 0){
                $route = 'admin.sea.index';
                $uri = route($route);
                $tabTitle = \App\Models\Permissions::where('uri', $route)->value('name');
            }
            return $this->response('添加客户成功', '0', ['uri'=>$uri, 'tabTitle'=>$tabTitle]);
        }
        return $this->response('添加客户失败');
    }


    /*客户修改*/
    public function edit($id)
    {
        $tags = [];
        $data = Customer::find($id);
        $res = Source::all();
        $tag = Tags::all();
        foreach($tag as $key=>$val){
            $tags[$key]['name'] = $val->tagname;
            $tags[$key]['value'] = $val->id;
            $tags[$key]['selected'] = $data->tags->contains($val->id) ? true : false;
        }
        return view('admin.customer.edit',['data'=>$data,'res'=>$res,'tags'=>$tags]);
    }

    /**
     * 编辑保存
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $id){
        $messages = [
            'company.required' => '公司名称不能为空',
            'name.required' => '联系人姓名不能为空',
            'phone.required' => '联系人手机号码不能为空',
            'source_id.required' => '客户来源不能为空',
            'phone.regex' => '手机号格式不正确'
        ];
        $validator = Validator::make($request->all(), [
            'company' => 'bail|required',
            'name' => 'bail|required',
            'phone' => 'bail|required|regex:/^1[3456789]\d{9}$/'
        ], $messages);

        if($validator->fails()){

            return $this->response($validator->errors()->first());
        }

        if($request->input('tel')){
            if(!preg_match('/^(\\+\\d{2}-)?0\\d{2,3}-\\d{7,8}$/', $request->input('tel'))){
                return $this->response('公司座机格式错误', 1);
            }
        }

        $tags = new Tags;
        $customer =  Customer::find($id);
        $tags->tags_id = $request->input('tags_id');
        $customer->company = $request->input('company');
        $customer->address = $request->input('address');
        $customer->source_id = $request->input('source_id');
        $customer->name = $request->input('name');
        $customer->sex = $request->input('sex', 0);
        $customer->phone = $request->input('phone');
        $customer->tel = $request->input('tel');
        $customer->emil = $request->input('emil');
        $customer->job = $request->input('job');
        $customer->username = $request->input('username');
        $customer->password = $request->input('password');
        $customer->status = $request->input('status');
        $customer->remarks = $request->input('remarks');
        // $id = Auth::guard('admin')->id();
        // $customer->uid = $id;
        if($customer->save()){

            $tags_id = explode(',', $tags->tags_id);
            if($tags_id[0] != ''){
                $customer->tags()->sync($tags_id);
            }
            return $this->response('编辑客户成功', '0');
        }
        return $this->response('编辑客户失败');
    }
    /*客户删除*/

    public function destroy(Request $request, $id){

        if(!$id) $id = $request->input('id');

        if(!$id) return $this->response('删除客户失败', 1);

        if(is_array($id)){
            if(Orders::whereIn('customer_id',$id)->count() > 0){
                return $this->response('该客户下还有订单,请先删除订单', 1);
            }
            $customer = Customer::whereIn('id', $id)->get();
        }
        else{
            if(Orders::where('customer_id',$id)->count() > 0){
                return $this->response('该客户下还有订单,请先删除订单', 1);
            }
            $customer = Customer::find($id);
        }

        if(Customer::destroy($id)){
            if(is_array($id)){
                foreach($customer as $val){
                    $val->tags()->detach();
                }
            }
            else{
                $customer->tags()->detach();
            }

            return $this->response('删除客户成功', 0);
        }

        return $this->response('删除客户失败', 1);
    }

    /**
     * 退回公海
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function return(Request $request){

        $cus_id = $request->input('id');
        $customer =  Customer::find($cus_id);
        if(!empty($cus_id) && !$customer->orders()->count()){
            $customer->status = 0;
            if($customer->save()){
                //退回客户记录
                $record = new ReceiveAssignRecord;
                $record->type = 3;
                $record->customer_id = $customer->id;
                $record->person_id = $customer->uid;
                $record->user_id = auth('admin')->user()->id;
                $record->save();

                return $this->response('退回客户成功', '0');
            }
        }

        return $this->response('退回客户失败，参数错误或已经有订单');
    }

    /**
     * 客户分析
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function analysi (Request $request)
    {
        $date = $request->input('date') ?: date('Y-m');
        $start = date('Y-m-01 00:00:00',strtotime($date));
        $end = date('Y-m-t 23:59:59',strtotime($date));
        $user = auth('admin')->user();

        //月新增客户
        $month_add_customer_count = Customer::where('status', 1)->whereBetween('created_at', [$start, $end])
            ->where(function($query) use($user){
                if(!$user->hasRole(1) && !$user->hasPermissionTo(46)){
                    $query->where('uid', $user->id);
                }
            })->count();

        //月拜访客户
        $mount_visit_customer_count = Customer::where('status', 1)->whereHas('remind', function($query) use($start, $end){
            $query->whereBetween('remind_time', [$start, $end]);
        })
        ->where(function($query) use($user){
            if(!$user->hasRole(1) && !$user->hasPermissionTo(46)){
                $query->where('admin_id', $user->id);
            }
        })->count();

        //月跟进客户
        $mount_with_customer_count = Customer::where('status', 1)->where(function($query) use($user){
            if(!$user->hasRole(1) && !$user->hasPermissionTo(46)){
                $query->where('uid', $user->id);
            }
        })
        ->whereHas('fup', function($query) use($start, $end){
            $query->whereBetween('created_at', [$start, $end]);
        })->count();

        //未跟进客户
        $mount_no_with_customer_count = Customer::where('status', 1)->whereBetween('created_at', [$start, $end])->doesntHave('fup')->count();
        
        //成交的客户
        $mount_deal_customer_count = Customer::whereHas('orders', function($query) use($start, $end, $user){
            $query->whereBetween('created_at', [$start, $end])->where('status', 2);
            $query->where(function($squery) use($user){
                if(!$user->hasRole(1) && !$user->hasPermissionTo(46)){
                    $squery->where('user_id', $user->id);
                }
            });
        })->count();

        $dates = date('t');
        $days = '';

        for($i = 1; $i <= $dates; $i++){
            $days .= $i.',';
        }

        $add_count = [];
        $order_money = [];
        //新增客户
        $newCustomers = Customer::select('created_at')->whereBetween('created_at', [$start, $end])
            ->where(function($query) use($user){
                if(!$user->hasRole(1) && !$user->hasPermissionTo(46)){
                    $query->where('uid', $user->id);
                }
            })
            ->get();
        $i = 0;
        $firstDay = date('Y-m-01');
        $lastDay = date('Y-m-d', strtotime("$firstDay +1 month -1 day"));
        $monthDay = date('Y-m-d', strtotime("$firstDay +$i days"));
        
        while ($monthDay <= $lastDay) {
            $monthDay = date('Y-m-d', strtotime("$firstDay +$i days"));
            $tmpCustomerCount = 0;
            $tmpAmount = 0;
            foreach($newCustomers as $tmpCustomer){
                if(date('Y-m-d', strtotime($tmpCustomer->created_at)) == $monthDay){
                    $tmpCustomerCount++;
                }
            }

            $add_count[] = $tmpCustomerCount;
            $i++;
        }
        $add_count = implode(',', $add_count);

        return view('admin.customer.analysi',[
            'date'=>$date,
            'month_add_customer_count' => $month_add_customer_count,
            'mount_visit_customer_count' => $mount_visit_customer_count,
            'mount_with_customer_count' => $mount_with_customer_count,
            'mount_no_with_customer_count' => $mount_no_with_customer_count,
            'mount_deal_customer_count' => $mount_deal_customer_count,
            'days' => $days,
            'add_count' => $add_count,
        ]);
    }

    //客户领取分配记录
    public function record($id)
    {
        $customer = [] ;
        $lists = ReceiveAssignRecord::where('customer_id',$id)->orderBy('created_at','desc')->get();
        $customers = Customer::all();
        foreach ($customers as $key => $value) {
            $customer[$value->id] = $value->company;
        }
        $users = Admin::all();
        foreach ($users as $key => $value) {
            $user[$value->id] = $value->nickname ? $value->nickname : $value->name;
        }
        return view('admin.customer.record',compact('id','lists','customer','user'));
    }
}
