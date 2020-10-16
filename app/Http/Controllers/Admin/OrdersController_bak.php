<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\BackendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Orders;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\Provinces;
use App\Models\Receivables;
use App\Models\Contract;
use App\Models\Refund;
use App\Models\Roles;
use App\Models\OrderActionRecord;
use App\Admin;
use App\Models\Files;
use App\Models\GoodProvinces;
use App\Models\GoodProvincesRecord;
use App\Events\SystemLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

//订单管理
class OrdersController extends BackendController
{
    protected $jsprocess = [
        '1' => '待处理',
        '2' => '备案准备材料',
        '3' => '备案提交，评测中',
        '4' => '备案评测通过',
        '5' => '机房准备材料',
        '6' => '机房提交，评测中',
        '7' => '机房评测未通过',
        '8' => '机房评测通过',
        '9' => '信安设备发货',
        '10' => '信安设备上架',
        '11' => '内测',
        '12' => '信安提交，评测中',
        '13' => '信安评测未通过，重新评测',
        '14' => '信安评测通过',
        '15' => '已退回',
        '18' => '已完成'
    ];
    protected $waprocess = [
        '1' => '待处理',
        '2' => '网审准备材料',
        '3' => '材料齐全，网审',
        '4' => '网审通过',
        '5' => '网审未过，重新审核',
        '6' => '拿证',
        '18' => '已完成'
    ];

    protected $orderType = [
        '1' => '办证',
        '2' => '变更',
        '3' => '年审'
    ];

    protected $verify = ['待审核','审核通过','审核未通过'];

    //订单列表
    public function index(Request $request)
    {
        $order_num = $request->input('order_num');
        $order_type = $request->input('order_type');
        $customer_id = '';
        if($request->input('customer_id')){
            $customer_id = Customer::where('company','like','%'.$request->input('customer_id').'%')->first('id');
        }

        $increment = $request->input('increment');
        $licence = $request->input('licence');
        $licence_start = $request->input('licence_start');
        $licence_end = $request->input('licence_end');
        $user_id = $request->input('user_id');
        $process = $request->input('process');
        $type = $request->input('hktype');
        $orders_id = Receivables::get(); //回款的订单查询
        $order_id = [];
        foreach($orders_id as $val){
            $order_id[]= $val['order_id'];
        }
        $where = [];
        $customer = [];
        if ($order_num) {
            $where[] = ['order_num','like','%'.$order_num.'%'];
        }
        if ($order_type) {
            $where['order_type'] = $order_type;
        }
        if ($customer_id) {
            $where['customer_id'] = $customer_id['id'];
        }
        if ($increment) {
            $where[] = ['increment','like','%'.$increment.'%'];
        }
        if ($licence) {
            $where[] = ['licence','like','%'.$licence.'%'];
        }
        if ($licence_start) {
            $where[] = ['licence_start','>=',strtotime($licence_start)];
        }
        if ($licence_end) {
            $where[] = ['licence_end','<=',strtotime($licence_end)];
        }
        if ($user_id) {
            $where['user_id'] = $user_id;
        }
        if($type == 3){
            $where[] = ['process','<>',18];
        }
        if($type == 2){
            $where[] = ['process',18];
        }

        //不同部门订单
        $role = $request->input('role');

        $admin = auth('admin')->user();
        $lists = Orders::where($where);
        if($type == 1){
            $lists = $lists->whereIn('id', $order_id);
        }
        $lists = $lists->where(function($query) use ($process){
                    if($process){
                        $query->whereRaw("find_in_set($process,process)");
                    }
                });

        if($role){
            $lists = $lists->WhereHas('orderActionRecord', function($oquery) use($role){
                        $oquery->whereHas('users', function($squery) use($role){
                            $squery->whereHas('roles', function($rquery) use($role){
                                $rquery->where('id', $role);
                            });
                        });
                    });
            //销售部
            if($role == 2){
                $lists = $lists->orWhereHas('user', function($query) use($role){
                    $query->whereHas('roles', function($rquery) use($role){
                        $rquery->where('id', $role)->orWhere('id', 1);
                    });
                });
            }
        }
        else{
            $lists = $lists->where(function($query) use($admin, $role){
                    if(!$admin->hasRole(1) && !$admin->hasPermissionTo(46)){

                        $query->where('user_id', $admin->id);

                        $query->orWhereHas('orderActionRecord', function($query) use($admin){
                            $query->whereHas('users', function($squery) use($admin){
                                $squery->where('user_id', $admin->id);
                            });
                        });
                    }
                });
        }
         
        $lists = $lists->orderBy('id','desc')->paginate(10)->appends($request->all());


        $users = Admin::whereHas('roles', function($query){
                    $query->where('id', 2);
                })->get();

        $customers = Customer::all();
        foreach ($customers as $key => $value) {
            $customer[$value['id']] = $value['company'];
        }
        $process = $this->waprocess;
        $orderType = $this->orderType;
        $verify = $this->verify;
        $show = auth('admin')->user()->hasPermissionTo(89);
        $roles  = $admin->roles->first()->id;

        return view('admin.orders.index', compact('lists','roles','users','customer','process','orderType','verify','show','admin', 'role'));
    }

    /**
     * 创建
     * @return [type] [description]
     */
    public function create()
    {
        $process = $this->waprocess;

        $customer = [];
        $good = [];
        $province = [];
        $users = [];
        $username = [];
        $customers = Customer::where('status',1)
            ->where(function($query){
                $user = auth('admin')->user();
                if(!$user->hasRole(1) && !$user->hasPermissionTo(46)){
                    $query->where('uid', $user->id);
                }
            })
            ->get();
        foreach ($customers as $key => $value) {
            $customer[$value['id']] = $value['company'];
        }
        $goods = Goods::where('status',1)->get();
        foreach ($goods as $key => $value) {
            $good[$value['id']] = $value['name'];
        }
        $provinces = Provinces::where('parent_id',0)->orderBy('id', 'asc')->get();
        foreach ($provinces as $key => $val) {
            $province[$key]['name'] = $val->name;
            $province[$key]['value'] = $val->id;
        }

        //手动流转人员
        $tmpUserNames = Admin::where('grade_id', 1)->where('id','<>',1)->where('id','<>',auth('admin')->user()->id)->whereHas('roles', function(Builder $query){$query->where('id', '<>', 2);})->get();  
        foreach($tmpUserNames as $key=>$val){
            $username[$key]['name'] = ($val->nickname ?: $val->name).'('.$val->roles->first()->name.')';
            $username[$key]['value'] = $val->id;
        }

        $user = auth('admin')->user();
        $roles  = $user->roles->first()->id;
        return view('admin.orders.create', compact('process','customer','good','province','roles','username'));
    }

    /**
     * 保存
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request){
        $messages = [
            'customer.required' => '客户名称不能为空',
            'plannedamt.required' => '合同金额不能为空',
            'plannedamt.numeric' => '合同金额格式错误',
        ];
        $validator = Validator::make($request->all(), [
            'customer' => 'bail|required',
            'plannedamt' => 'bail|required|numeric',
        ], $messages);

        $request->flash();

        if($validator->fails()){

            return back()->with('msg', $validator->errors()->first())->with('code', 1);
        }

        $money_reg = '/^[1-9]\d*|^[1-9]\d*.\d+[1-9]$/';
        if(!preg_match($money_reg, $request->input('plannedamt')) || $request->input('plannedamt') == '0'){
            return back()->with('msg', '合同金额格式错误')->with('code', 1);
        }
        if($request->input('spend')){
            if(!preg_match($money_reg, $request->input('spend'))){
                return back()->with('msg', '商务费用金额格式错误')->with('code', 1);
            }
        }
        $province = $request->input('province');
        $city = $request->input('city');
        $stages = $request->input('stages');

        if($province[0] == null && $city[0] == null){

            return back()->with('msg', '分布节点省或市必须填写其中一项')->with('code', 1);
        }
        if($stages[0] == null){

            return back()->with('msg', '第一期金额不能为空')->with('code', 1);
        }

        if(array_sum($stages) != $request->input('plannedamt')){
            return back()->with('msg', '分期总金额与合同金额不符')->with('code', 1);
        }

        if($request->input('con_id') == '1'){
            if($request->input('title') == ''){

                return back()->with('msg', '合同标题不能为空')->with('code', 1);
            }
        }

        \DB::beginTransaction();

        try{
            $User = auth('admin')->user();
            $orders = new Orders; //订单

            $orders->order_num = 'D'.date('Ymd').time();
            $orders->customer_id = $request->input('customer');
            $orders->user_id = $User->id;
            $orders->process = 1;
            $orders->plannedamt = $request->input('plannedamt');
            $orders->spend = $request->input('spend');
            $orders->stages = serialize($stages);
            $orders->remarks = $request->input('remarks');

            if($orders->save()){
                //节点数据
                $rgoods = $request->input('goods');
                $rprovince = $request->input('province');
                $rcity = $request->input('city');
                $rreview = $request->input('review');
                $rnetwork = $request->input('network');
                $goodsCity = $this->doGoodsCity($rgoods, $rprovince, $rcity, $rreview, $rnetwork, $orders->id, $User->id);

                $goodProvinces = new GoodProvinces;
                $goodProvinces->insert($goodsCity);

                if($request->input('process')){
                    $process = $this->waprocess;
                    $pro = $process[$request->input('process')];
                    $action = new OrderActionRecord;
                    $action->order_id = $orders->id;
                    $action->user_id = $User->id;
                    $action->content = '业务状态：'.$pro;
                    $action->save();
                }

                if($request->input('con_id') == '1'){
                    $insertid = $orders->id;
                    $contract = new Contract;
                    $contract->customer_id = $request->input('customer');
                    $contract->order_id = $insertid;
                    $contract->title = $request->input('title');
                    $contract->number = $request->input('number');
                    $contract->starttime = strtotime($request->input('starttime'));
                    $contract->endtime = strtotime($request->input('endtime'));
                    $contract->remark = $request->input('notes');

                    if($contract->save() && ($request->hasFile('picture') || $request->hasFile('annex'))){
                        $picture = $request->file('picture');
                        $annex = $request->file('annex');
                        $picturePath = [];
                        $annexPath = [];
                        $url_path = '/uploads/'.date('Y-m-d');
                        foreach ($picture as $key => $value) {
                            if(!empty($value)){
                                $extension = $value->getClientOriginalExtension();
                                $fileName = date('YmdHis').mt_rand(100,999).'.'.$extension;
                                $picturefile = $value->storeAs($url_path,$fileName);
                                $picturePath[] = $picturefile;
                            }
                        }
                        foreach ($annex as $key => $value) {
                            if(!empty($value)){
                                $extension = $value->getClientOriginalExtension();
                                $fileName = date('YmdHis').mt_rand(100,999).'.'.$extension;
                                $annexfile = $value->storeAs($url_path,$fileName);
                                $annexPath[] = $annexfile;
                            }
                        }

                        foreach ($picturePath as $key => $value) {
                            $files = new Files;
                            $fileid = $contract->id;
                            $files->order_id = $insertid;
                            $files->contract_id = $fileid;
                            $files->type = 1;
                            $files->link = $value;
                            $re = $files->save();
                        }

                        foreach ($annexPath as $k => $v) {
                            $filed = new Files;
                            $fileid = $contract->id;
                            $filed->order_id = $insertid;
                            $filed->contract_id = $fileid;
                            $filed->type = 2;
                            $filed->link = $v;
                            $res = $filed->save();
                        }
                    }
                }

            //流转
            $tmpid = Admin::where('grade_id', 1)->whereHas('roles', function(Builder $query){$query->where('id', 2);})->value('id');  
            if(empty($request->input('user_id')) && auth('admin')->user()->id != $tmpid){
                $users_id = explode(',', $tmpid);
            }elseif(empty($request->input('user_id')) && auth('admin')->user()->id == $tmpid){
                $users_id = [];
            }else{
                $users_id = explode(',', $request->input('user_id').','.$tmpid);//分配的用户id
            }
            //分配记录
            if(!empty($users_id)){
                $tmpUserNames = Admin::whereIn('id',$users_id)->get();
                $userNames = [];
                foreach($tmpUserNames as $key=>$user){
                    $role = $user->roles->first()->nickname ?: $user->roles->first()->name;
                    $userNames[$key] = ($user->nickname ? $user->nickname : $user->name).'('.$role.')';
                }
                $tmpRecord['user_id'] = $User->id;
                $tmpRecord['order_id'] = $orders->id;
                $tmpRecord['content'] = '流转部门：'.implode('|', $userNames);
                $record = $orders->orderActionRecord()->save(new OrderActionRecord($tmpRecord));//分配订单记录
                $record->users()->attach($users_id);
            }

                //订单分配提醒
                if($users_id){
                    $this->createRemind('有订单('.$orders->order_num.')已流转到您，请到订单列表查看', '', 'Order', $users_id);
                }

                event(new SystemLog('添加订单成功'));
            }
        } catch(\Illuminate\Database\QueryException $ex){
            \DB::rollback();

            return back()->with('msg', '添加失败')->with('code', 1);
        }

        \DB::commit();
        return back()->with('code', 0);
    }

    /**
     * 显示
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function show($id){
        $users = Admin::all();
        foreach ($users as $key => $value) {
            $user[$value['id']] = $value;
        }
        $customer = [];
        $customers = Customer::where('status',1)->get();
        foreach ($customers as $key => $value) {
            $customer[$value['id']] = $value;
        }
        $goods = Goods::where('status',1)->get();
        foreach ($goods as $key => $value) {
            $good[$value['id']] = $value['name'];
        }
        $provinces = Provinces::all();
        foreach ($provinces as $key => $value) {
            $province[$value['id']] = $value['name'];
        }
        $list = Orders::find($id);

        $stagesarr = unserialize($list['stages']);
        $arr = [];
        foreach ($stagesarr as $key => $value) {
            if(is_numeric($value)){
                $arr[] = 1;
            }
        }

        $arr_count = count($arr);
        $stagesinfo = array_chunk($stagesarr, $arr_count);

        $contract = Contract::where('order_id',$id)->first();
        $picture = Files::where(['order_id'=>$id,'type'=>1])->get();
        $annex = Files::where(['order_id'=>$id,'type'=>2])->get();
        $distribute = OrderActionRecord::where('order_id',$id)->orderBy('updated_at','desc')->get();
        $orderType = $this->orderType;
        $show = auth('admin')->user()->hasPermissionTo(89);
        $name = auth('admin')->user();
        $roles  = $name->roles->first()->id;
        //技术部
        if($roles == 4){
            $goodProvinces = $list->goodProvinces->where('review', 1)->groupBy('good_id');
        }
        else{
            $goodProvinces = $list->goodProvinces->groupBy('good_id');
        }

        $goodProvince = [];
        foreach($goodProvinces as $key=>$pro){
            foreach($pro as $i=>$info){
                $goodProvince[$key][$i]['id'] = $info->provinces;
                $goodProvince[$key][$i]['review'] = $info->review;
                $goodProvince[$key][$i]['network'] = $info->network;
            }
        }

        // dd($goodProvince);

        return view('admin.orders.show',compact('list','user','distribute','customer','contract','picture','annex','goodProvince','good','province','orderType','show','name','roles','stagesinfo'));
    }
    //审核
    public function verify(Request $request){
        $id = $request->input('id');
        $orders = Orders::find($id);
        if($request->input('jsverify') || $request->input('jsverify') == '0'){
            $orders->jsverify = $request->input('jsverify');
        }elseif($request->input('waverify') || $request->input('waverify') == '0'){
            $orders->waverify = $request->input('waverify');
        }
        $orders->content = $request->input('content');

        if($orders->save()){
            $verify = $this->verify;
            if($request->input('jsverify') || $request->input('jsverify') == '0'){
                $pro = '技术部'.$verify[$request->input('jsverify')];
            }else{
                $pro = '文案部'.$verify[$request->input('waverify')];
            }

            $action = new OrderActionRecord;
            $action->order_id = $id;
            $action->user_id = auth('admin')->user()->id;
            $action->content = '审核状态：'.$pro;
            $action->remarks = $request->input('content');
            $action->save();

            return $this->response('订单审核成功', 0);
        }else{
            return $this->response('订单审核失败', 1);
        }

    }
    /**
     * 编辑
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id){
        $customer = [];
        $username = [];
        $list = Orders::find($id);
        $process = $this->waprocess;
        $customers = Customer::where('status',1)
            ->where(function($query) use ($list){
                $user = auth('admin')->user();
                if(!$user->hasRole(1) && !$user->hasPermissionTo(46)){
                    $query->where('uid', $list->user_id);
                }
            })
            ->get();
        foreach ($customers as $key => $value) {
            $customer[$value['id']] = $value['company'];
        }
        $goods = Goods::where('status',1)->get();
        foreach ($goods as $key => $value) {
            $good[$value['id']] = $value['name'];
        }

        $process_id = explode(',',$list['process']);

        //获取当前用户身份
        $user = auth('admin')->user();
        $role  = $user->roles->first()->id;
        //技术主管和文案主管id
        // $ldid = Admin::where('grade_id', 1)->whereHas('roles', function(Builder $query){$query->where('id', 4)->orWhere('id', 3);})->get('id')->toArray();
        // foreach ($ldid as $key => $value) {
        //     $ldid[$key] = $value['id'];
        // }
        //技术部流转人员
        if($role == 4){
            $tmpUserNames = Admin::where('id','<>',1)->where('id','<>',auth('admin')->user()->id)->whereHas('roles', function(Builder $query){$query->where('id',4);})->get();  

            foreach($tmpUserNames as $key=>$val){
                $username[$key]['name'] = ($val->nickname ?: $val->name).'('.$val->roles->first()->name.')';
                $username[$key]['value'] = $val->id;
            }

        }
        //文案部流转人员
        elseif($role == 3){
            $tmpUserNames = Admin::where('id','<>',1)->where('id','<>',auth('admin')->user()->id)->whereHas('roles', function(Builder $query){$query->where('id',3);})->get();  

            foreach($tmpUserNames as $key=>$val){
                $username[$key]['name'] = ($val->nickname ?: $val->name).'('.$val->roles->first()->name.')';
                $username[$key]['value'] = $val->id;
            }
        }else{
            $tmpUserNames = Admin::where('grade_id', 1)->where('id','<>',1)->where('id','<>',auth('admin')->user()->id)->whereHas('roles', function(Builder $query){$query->where('id', '<>', 2);})->get();  
            foreach($tmpUserNames as $key=>$val){
                $username[$key]['name'] = ($val->nickname ?: $val->name).'('.$val->roles->first()->name.')';
                $username[$key]['value'] = $val->id;
            }
        }

        $contract = Contract::where('order_id',$id)->first();
        $imageinfo = Files::where(['order_id'=>$id,'type'=>1])->get();
        $annexinfo = Files::where(['order_id'=>$id,'type'=>2])->get();
        
        $stagesarr = unserialize($list['stages']);
        $arr = [];
        foreach ($stagesarr as $key => $value) {
            if(is_numeric($value)){
                $arr[] = 1;
            }
        }

        $arr_count = count($arr);
        $stagesinfo = array_chunk($stagesarr, $arr_count);

        $provinces = Provinces::where('parent_id',0)->orderBy('id', 'asc')->get();

        foreach ($provinces as $key => $value) {
            $prov[$key]['name'] = $value->name;
            $prov[$key]['value'] = $value->id;
        }

        //技术部
        if($role == 4){
            $goodProvinces = $list->goodProvinces->where('review', 1)->groupBy('good_id');
        }
        else{
            $goodProvinces = $list->goodProvinces->groupBy('good_id');
        }

        $goodProvince = [];
        foreach($goodProvinces as $key=>$pro){
            $city = [];
            foreach($pro as $i=>$info){
                $province_id = $info->provinces;
                $city_id = 0;
                if(Provinces::where('id', $info->provinces)->where('parent_id', 0)->doesntExist()){
                    $province_id = Provinces::where('id', $info->provinces)->value('parent_id');
                    $city_id = $info->provinces;
                }

                foreach($provinces as $k=>$v){
                    $goodProvince[$key]['province'][$k]['name'] = $v->name;
                    $goodProvince[$key]['province'][$k]['value'] = $v->id;
                    if($province_id == $v->id){
                       $goodProvince[$key]['province'][$k]['selected'] = true;
                       if(!$city_id){
                            $goodProvince[$key]['province'][$k]['review'] = $info->review;
                            $goodProvince[$key]['province'][$k]['network'] = $info->network;
                        }
                    }
                }

                if($city_id){
                    $citys = Provinces::where('parent_id', $province_id)->get();
                    foreach ($citys as $k => $value) {
                        $city[$province_id][$k]['name'] = $value->name;
                        $city[$province_id][$k]['value'] = $value->id;
                        if($value->id == $city_id){
                            $city[$province_id][$k]['selected'] = true;    
                        }
                        if($value->id == $city_id && $info->review){
                            $city[$province_id][$k]['review'] = $info->review;
                        }
                    }
                }
            }
            if(count($city) > 1){
                $city = array_merge(...$city);
            }
            elseif($city){
                sort($city);
                $city = $city[0];
            }
            $goodProvince[$key]['city'] = $city;
        }

        if(!$goodProvince){
            $goodProvince = [['province'=>$prov]];
        }
        // dd($list);

        return view('admin.orders.edit', compact('list', 'process', 'customer', 'good', 'process_id', 'contract', 'imageinfo', 'annexinfo', 'stagesinfo', 'goodProvince', 'prov', 'user','role', 'username'));
    }

    /**
     * 编辑保存
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request,$id){
        $power = auth('admin')->user();
        $roles  = $power->roles->first()->id;
        if($roles == 3){
            $messages = [
                'required' => '业务状态不能为空'
            ];
            $validator = Validator::make($request->all(), [
                'process' => 'bail|required',
            ], $messages);

            if($validator->fails()){

                return back()->with('msg', $validator->errors()->first())->with('code', 1);
            }

            \DB::beginTransaction();
            try{
                $orders = Orders::find($id);
                $orders->process = $request->input('process');
                $orders->notes = $request->input('remarks');
                $orders->order_type = $request->input('order_type');
                $orders->increment = $request->input('increment');
                $orders->licence = $request->input('licence');
                $orders->licence_start = strtotime($request->input('licence_start'));
                $orders->licence_end = strtotime($request->input('licence_end'));

                if($orders->save()){

                    $pro = '';
                    if($request->input('process')){
                        $process = $this->waprocess;
                        $action = new OrderActionRecord;
                        $action->order_id = $orders->id;
                        $action->user_id = auth('admin')->user()->id;
                        $action->content = '业务状态：'.$process[$request->input('process')];
                        $action->remarks = $request->input('remarks');
                        $action->save();
                    }

                //流转
                if(empty($request->input('user_id'))){
                    $users_id = [];
                }
                else{
                    $users_id = explode(',', $request->input('user_id') );//分配的用户id
                }
                //分配记录
                if(!empty($users_id)){
                    $tmpUserNames = Admin::whereIn('id',$users_id)->get();
                    $userNames = [];
                    foreach($tmpUserNames as $key=>$user){
                        $role = $user->roles->first()->nickname ?: $user->roles->first()->name;
                        $userNames[$key] = ($user->nickname ? $user->nickname : $user->name).'('.$role.')';
                    }
                    $tmpRecord['user_id'] = auth('admin')->user()->id;
                    $tmpRecord['order_id'] = $orders->id;
                    $tmpRecord['content'] = '流转部门：'.implode('|', $userNames);
                    $record = $orders->orderActionRecord()->save(new OrderActionRecord($tmpRecord));//分配订单记录
                    $record->users()->attach($users_id);
                }
                //订单分配提醒
                if($users_id){
                    $this->createRemind('有订单('.$orders->order_num.')已流转到您，请到订单列表查看', '', 'Order', $users_id);
                }

                    event(new SystemLog('编辑订单成功'));
                }
            } catch(\Illuminate\Database\QueryException $ex){
                \DB::rollback();

                return back()->with('msg', '添加失败')->with('code', 1);
            }

            \DB::commit();
            return back()->with('code', 0);
            
        }
        elseif($roles == 4){
            $orders = Orders::find($id);
            //流转
            if(empty($request->input('user_id'))){
                $users_id = [];
            }
            else{
                $users_id = explode(',', $request->input('user_id') );//分配的用户id
            }
            
            //分配记录
            \DB::beginTransaction();
            try{
                if(!empty($users_id)){
                    $tmpUserNames = Admin::whereIn('id',$users_id)->get();
                    $userNames = [];
                    foreach($tmpUserNames as $key=>$user){
                        $role = $user->roles->first()->nickname ?: $user->roles->first()->name;
                        $userNames[$key] = ($user->nickname ? $user->nickname : $user->name).'('.$role.')';
                    }
                    $tmpRecord['user_id'] = auth('admin')->user()->id;
                    $tmpRecord['order_id'] = $id;
                    $tmpRecord['content'] = '流转部门：'.implode('|', $userNames);
                    $record = $orders->orderActionRecord()->save(new OrderActionRecord($tmpRecord));//分配订单记录
                    $record->users()->attach($users_id);
                }
                //订单分配提醒
                if($users_id){
                    $this->createRemind('有订单('.$orders->order_num.')已流转到您，请到订单列表查看', '', 'Order', $users_id);
                }

                event(new SystemLog('编辑订单成功'));

            } catch(\Illuminate\Database\QueryException $ex){
                \DB::rollback();

                return back()->with('msg', '添加失败')->with('code', 1);
            }

            \DB::commit();
            return back()->with('code', 0);
            
        }elseif($roles == 2){
            $messages = [
                'customer.required' => '客户名称不能为空',
                'plannedamt.required' => '合同金额不能为空',
                'plannedamt.numeric' => '合同金额格式错误',
            ];
            $validator = Validator::make($request->all(), [
                'customer' => 'bail|required',
                'plannedamt' => 'bail|required|numeric',
            ], $messages);

            if($validator->fails()){

                return back()->with('msg', $validator->errors()->first())->with('code', 1);
            }

            $money_reg = '/^[1-9]\d*|^[1-9]\d*.\d+[1-9]$/';
            if(!preg_match($money_reg, $request->input('plannedamt')) || $request->input('plannedamt') == '0'){
                return back()->with('msg', '合同金额格式错误')->with('code', 1);
            }
            if($request->input('spend')){
                if(!preg_match($money_reg, $request->input('spend'))){
                    return back()->with('msg', '商务费用金额格式错误')->with('code', 1);
                }
            }
            $province = $request->input('province');
            $city = $request->input('city');
            $stages = $request->input('stages');

            if($province[0] == null && $city[0] == null){

                return back()->with('msg', '分布节点省或市必须填写其中一项')->with('code', 1);
            }
            if($stages[0] == null){

                return back()->with('msg', '第一期金额不能为空')->with('code', 1);
            }

            if(array_sum($stages) != $request->input('plannedamt')){
                return back()->with('msg', '分期总金额与合同金额不符')->with('code', 1);
            }
            
            \DB::beginTransaction();
            try{
                $User = auth('admin')->user();
                $orders = Orders::find($id);
                $orders->customer_id = $request->input('customer');
                $orders->plannedamt = $request->input('plannedamt');
                $orders->spend = $request->input('spend');
                $orders->stages = serialize($stages);
                $orders->remarks = $request->input('comments');

                if($orders->save()){
                    GoodProvinces::where('order_id',$id)->delete();
                    //节点数据
                    $rgoods = $request->input('goods');
                    $rreview = $request->input('review');
                    $rnetwork = $request->input('network');
                    $goodsCity = $this->doGoodsCity($rgoods, $province, $city, $rreview, $rnetwork, $orders->id, $User->id);
                    
                    $goodProvinces = new GoodProvinces;
                    $goodProvinces->insert($goodsCity);
                    if($request->input('con_id') == '1'){
                        $contract->customer_id = $request->input('customer');
                        $contract->order_id = $id;
                        $contract->title = $request->input('title');
                        $contract->number = $request->input('number');
                        $contract->starttime = strtotime($request->input('starttime'));
                        $contract->endtime = strtotime($request->input('endtime'));
                        $contract->remark = $request->input('notes');

                        if($contract->save()){

                            $picture = $request->file();
                            $picturePath = [];
                            $url_path = '/uploads/'.date('Y-m-d');
                            foreach ($picture as $key => $value) {

                                if(!empty($value)){
                                    $extension = $value->getClientOriginalExtension();
                                    $fileName = date('YmdHis').mt_rand(100,999).'.'.$extension;
                                    $picturefile = $value->storeAs($url_path,$fileName);
                                    $picturePath[$key] = $picturefile;
                                }
                            }

                            foreach ($picturePath as $key => $value) {
                                if(preg_match('/\d+/',$key,$arr)){
                                    $fjinfo = Files::where('id',$arr[0])->first();
                                    if($fjinfo){
                                        @unlink(public_path() .'/'. $fjinfo['link']);
                                        $files = Files::find($arr[0]);
                                        $files->order_id = $id;
                                        $files->contract_id = $contract->id;
                                        $files->link = $value;
                                        $files->save();
                                    }else{
                                        $filed = new Files;
                                        $filed->order_id = $id;
                                        $filed->contract_id = $contract->id;
                                        if(substr($key,0,7) == 'picture'){
                                            //图片
                                            $filed->type = 1;
                                        }else{
                                            //附件
                                            $filed->type = 2;
                                        }
                                        $filed->link = $value;
                                        $filed->save();
                                    }
                                }
                            }

                        }
                    }
                    //流转
                    if(empty($request->input('user_id'))){
                        $users_id = [];
                    }
                    else{
                        $users_id = explode(',', $request->input('user_id'));//分配的用户id
                    }
                    
                    //分配记录
                    if(!empty($users_id)){
                        $tmpUserNames = Admin::whereIn('id',$users_id)->get();
                        $userNames = [];
                        foreach($tmpUserNames as $key=>$user){
                            $role = $user->roles->first()->nickname ?: $user->roles->first()->name;
                            $userNames[$key] = ($user->nickname ? $user->nickname : $user->name).'('.$role.')';
                        }
                        $tmpRecord['user_id'] = auth('admin')->user()->id;
                        $tmpRecord['order_id'] = $orders->id;
                        $tmpRecord['content'] = '流转部门：'.implode('|', $userNames);
                        $record = $orders->orderActionRecord()->save(new OrderActionRecord($tmpRecord));//分配订单记录
                        $record->users()->attach($users_id);
                    }
                    //订单分配提醒
                    if($users_id){
                        $this->createRemind('有订单('.$orders->order_num.')已流转到您，请到订单列表查看', '', 'Order', $users_id);
                    }
                    event(new SystemLog('编辑订单成功'));
                }
            } catch(\Illuminate\Database\QueryException $ex){
                \DB::rollback();

                return back()->with('msg', '编辑失败')->with('code', 1);
            }

            \DB::commit();
            return back()->with('code', 0);

        }elseif($roles == 5){
            $orders = Orders::find($id);
            $stages = $request->input('stages');
            $billing_time = $request->input('billing_time');
            $collection_time = $request->input('collection_time');
            $result = array_merge($stages,$billing_time,$collection_time);
            
            \DB::beginTransaction();
            try{
                $orders->stages = serialize($result);
                if($orders->save()){
                    event(new SystemLog('编辑订单成功'));
                }
            } catch(\Illuminate\Database\QueryException $ex){
                \DB::rollback();

                return back()->with('msg', '编辑失败')->with('code', 1);
            }

            \DB::commit();
            return back()->with('code', 0);
        }
        elseif($roles == 1){
            $messages = [
                'customer.required' => '客户名称不能为空',
                'plannedamt.required' => '合同金额不能为空',
                'process.required' => '业务状态不能为空',
                'plannedamt.numeric' => '合同金额格式错误',
            ];
            $validator = Validator::make($request->all(), [
                'customer' => 'bail|required',
                'process' => 'bail|required',
                'plannedamt' => 'bail|required|numeric',
            ], $messages);

            if($validator->fails()){

                return back()->with('msg', $validator->errors()->first())->with('code', 1);
            }

            $money_reg = '/^[1-9]\d*|^[1-9]\d*.\d+[1-9]$/';
            if(!preg_match($money_reg, $request->input('plannedamt')) || $request->input('plannedamt') == '0'){
                return back()->with('msg', '合同金额格式错误')->with('code', 1);
            }
            if($request->input('spend')){
                if(!preg_match($money_reg, $request->input('spend'))){
                    return back()->with('msg', '商务费用金额格式错误')->with('code', 1);
                }
            }
            $province = $request->input('province');
            $city = $request->input('city');
            $stages = $request->input('stages');
            $billing_time = $request->input('billing_time');
            $collection_time = $request->input('collection_time');
            $result = array_merge($stages,$billing_time,$collection_time);

            if($province[0] == null && $city[0] == null){

                return back()->with('msg', '分布节点省或市必须填写其中一项')->with('code', 1);
            }
            if($stages[0] == null){

                return back()->with('msg', '第一期金额不能为空')->with('code', 1);
            }

            if(array_sum($stages) != $request->input('plannedamt')){
                return back()->with('msg', '分期总金额与合同金额不符')->with('code', 1);
            }

            if($request->input('con_id') == '1'){
                if($request->input('title') == ''){

                    return back()->with('msg', '合同标题不能为空')->with('code', 1);
                }
            }
            
            \DB::beginTransaction();
            try{
                $User = auth('admin')->user();
                $orders = Orders::find($id);

                $distribute = new OrderActionRecord;
                $contract = Contract::where('order_id',$id)->first();
                $orders->order_type = $request->input('order_type', 1);
                $orders->customer_id = $request->input('customer');
                $orders->increment = $request->input('increment');
                $orders->licence = $request->input('licence');
                $orders->licence_start = strtotime($request->input('licence_start'));
                $orders->licence_end = strtotime($request->input('licence_end'));
                $orders->process = $request->input('process');
                $orders->plannedamt = $request->input('plannedamt');
                $orders->spend = $request->input('spend');
                $orders->stages = serialize($result);
                $orders->remarks = $request->input('comments');
                $orders->notes = $request->input('remarks');

                if($orders->save()){
                    GoodProvinces::where('order_id',$id)->delete();
                    //节点数据
                    $rgoods = $request->input('goods');
                    $rreview = $request->input('review');
                    $rnetwork = $request->input('network');
                    $goodsCity = $this->doGoodsCity($rgoods, $province, $city, $rreview, $rnetwork, $orders->id, $User->id);
                    
                    $goodProvinces = new GoodProvinces;
                    $goodProvinces->insert($goodsCity);

                    $pro = '';
                    if($request->input('process')){
                        $process = $this->waprocess;
                        $proce = explode(',', $request->input('process'));
                        foreach ($proce as $key => $value) {
                            $pro .= $process[$value].',';
                        }

                        $action = new OrderActionRecord;
                        $action->order_id = $orders->id;
                        $action->user_id = auth('admin')->user()->id;
                        $action->content = '业务状态：'.$pro;
                        $action->remarks = $request->input('remarks');
                        $action->save();
                    }

                    if($request->input('con_id') == '1'){
                        $contract->customer_id = $request->input('customer');
                        $contract->order_id = $id;
                        $contract->title = $request->input('title');
                        $contract->number = $request->input('number');
                        $contract->starttime = strtotime($request->input('starttime'));
                        $contract->endtime = strtotime($request->input('endtime'));
                        $contract->remark = $request->input('notes');

                        if($contract->save()){

                            $picture = $request->file();
                            $picturePath = [];
                            $url_path = '/uploads/'.date('Y-m-d');
                            foreach ($picture as $key => $value) {

                                if(!empty($value)){
                                    $extension = $value->getClientOriginalExtension();
                                    $fileName = date('YmdHis').mt_rand(100,999).'.'.$extension;
                                    $picturefile = $value->storeAs($url_path,$fileName);
                                    $picturePath[$key] = $picturefile;
                                }
                            }

                            foreach ($picturePath as $key => $value) {
                                if(preg_match('/\d+/',$key,$arr)){
                                    $fjinfo = Files::where('id',$arr[0])->first();
                                    if($fjinfo){
                                        @unlink(public_path() .'/'. $fjinfo['link']);
                                        $files = Files::find($arr[0]);
                                        $files->order_id = $id;
                                        $files->contract_id = $contract->id;
                                        $files->link = $value;
                                        $files->save();
                                    }else{
                                        $filed = new Files;
                                        $filed->order_id = $id;
                                        $filed->contract_id = $contract->id;
                                        if(substr($key,0,7) == 'picture'){
                                            //图片
                                            $filed->type = 1;
                                        }else{
                                            //附件
                                            $filed->type = 2;
                                        }
                                        $filed->link = $value;
                                        $filed->save();
                                    }
                                }
                            }

                    }
                }
                if(empty($request->input('user_id'))){
                    $users_id = [];
                }else{
                    $users_id = explode(',', $request->input('user_id') );//分配的用户id
                }
                
                //分配记录
                if(!empty($users_id)){
                    $tmpUserNames = Admin::whereIn('id',$users_id)->get();
                    $userNames = [];
                    foreach($tmpUserNames as $key=>$user){
                        $role = $user->roles->first()->nickname ?: $user->roles->first()->name;
                        $userNames[$key] = ($user->nickname ? $user->nickname : $user->name).'('.$role.')';
                    }
                    $tmpRecord['user_id'] = auth('admin')->user()->id;
                    $tmpRecord['order_id'] = $orders->id;
                    $tmpRecord['content'] = '流转部门：'.implode(' | ', $userNames);
                    $record = $orders->orderActionRecord()->save(new OrderActionRecord($tmpRecord));//分配订单记录
                    $record->users()->attach($users_id);
                }
                //订单分配提醒
                if($users_id){
                    $this->createRemind('有订单('.$orders->order_num.')已流转到您，请到订单列表查看', '', 'Order', $users_id);
                }

                    event(new SystemLog('编辑订单成功'));
                }
            } catch(\Illuminate\Database\QueryException $ex){
                \DB::rollback();

                return back()->with('msg', '编辑失败')->with('code', 1);
            }

            \DB::commit();
            return back()->with('code', 0);
        }

        return back()->with('msg', '编辑失败')->with('code', 1);
    }

    /**
     * 删除\批量删除
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy(Request $request,$id){
        if(!$id) $id = $request->input('id');
        if(!$id) return $this->response('删除订单失败', 1);
        $image = [];
        if(is_array($id)){
            $orders = Orders::whereIn('id', $id)->get();
            $imageinfo = Files::whereIn('order_id',$id)->get();
        }
        else{
            $orders = Orders::find($id);
            $imageinfo = Files::where('order_id',$id)->get();
        }

        \DB::beginTransaction();
        try{
            if(Orders::destroy($id)){
                if(is_array($id)){
                    foreach ($id as $key => $value) {
                        OrderActionRecord::where('order_id',$value)
                        ->get()
                        ->each(function($OrderActionRecord){
                            $OrderActionRecord->users()->detach();
                            $OrderActionRecord->delete();
                        });
                        GoodProvinces::where('order_id',$value)->delete();
                        Receivables::where('order_id',$value)->delete();
                        Contract::where('order_id',$value)->delete();
                        Files::where('order_id',$value)->delete();
                        Refund::where('order_id',$value)->delete();
                        GoodProvincesRecord::where('order_id',$value)->delete();
                        foreach ($imageinfo as $key => $value) {
                            $image[$key] = $value['link'];
                        }
                        foreach ($image as $key => $value) {
                            @unlink(public_path() .'/'. $value);
                        }
                    }
                }else{
                    OrderActionRecord::where('order_id',$id)
                    ->get()
                    ->each(function($OrderActionRecord){
                        $OrderActionRecord->users()->detach();
                        $OrderActionRecord->delete();
                    });
                    GoodProvinces::where('order_id',$id)->delete();
                    Receivables::where('order_id',$id)->delete();
                    Contract::where('order_id',$id)->delete();
                    Files::where('order_id',$id)->delete();
                    Refund::where('order_id',$id)->delete();
                    GoodProvincesRecord::where('order_id',$id)->delete();
                    foreach ($imageinfo as $key => $value) {
                        $image[$key] = $value['link'];
                    }
                    foreach ($image as $key => $value) {
                        @unlink(public_path() .'/'. $value);
                    }
                }
            }
        } catch(\Illuminate\Database\QueryException $ex){
            \DB::rollback();

            return $this->response('删除订单失败', 1);
        }

        \DB::commit();

        return $this->response('删除订单成功', 0);
    }

    /**
     * 回款记录
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function back(Request $request){
        if($request->isMethod('post')){
            $messages = [
                'money.required' => '回款金额不能为空',
                'money.numeric' => '金额格式错误',
            ];
            $validator = Validator::make($request->all(), [
                'money' => 'bail|required|numeric',
            ], $messages);

            if($validator->fails()){

                return $this->response($validator->errors()->first());
            }
            $money_reg = '/^[1-9]\d*|^[1-9]\d*.\d+[1-9]$/';
            if(!preg_match($money_reg, $request->input('money')) || $request->input('money') == '0'){
                return $this->response('金额格式错误', 1);
            }
            $number = [];
            if($request->input('money_id')){
                $num_id = $request->input('numid');
                $numbers = Receivables::where('order_id',$num_id)->where('id','<>',$request->input('money_id'))->get();
            }else{
                $num_id = $request->input('id');
                $numbers = Receivables::where('order_id',$num_id)->get();
            }

            $plannedamt = Orders::where('id',$num_id)->first('plannedamt');
            foreach($numbers as $val){
                $number[$val['id']] = $val['money'];
            }
            $sum = array_sum($number);
            $surplus = ($plannedamt['plannedamt']-$sum)-$request->input('money');
            if($surplus < 0){
                return $this->response('回款金额已大于总金额,请重新填写!', 1);
            }

            if($request->input('money_id')){
                $receivables = Receivables::find($request->input('money_id'));;
                $receivables->money = $request->input('money');

                if($receivables->save()){
                    return $this->response('编辑回款成功', 0);
                }else{
                    return $this->response('编辑回款失败', 1);
                }
            }else{
                $receivables = new Receivables;
                $receivables->money = $request->input('money');
                $receivables->remark = $request->input('remark');
                $receivables->order_id = $request->input('id');
                $receivables->back_time = $request->input('back_time');

                if($receivables->save()){
                    $orders = Orders::where('id',$request->input('id'))->first();
                    $this->createRemind('订单('.$orders->order_num.')已回款'.$request->input('money').'元', '', 'Order', [$orders->user_id]);
                    return $this->response('添加回款成功', 0);
                }else{
                    return $this->response('添加回款失败', 1);
                }
            }
        }
        $list = [];
        $id = $request->input('id');
        $plannedamt = $request->input('plannedamt');
        $lists = Receivables::where('order_id',$id)->orderBy('updated_at','desc')->get();
        foreach($lists as $val){
            $list[$val['id']] = $val['money'];
        }
        $sum = array_sum($list);
        $all = $this->amount($plannedamt-$sum);
        $admin = auth('admin')->user();
        $roles  = $admin->roles->first()->id;
        return view('admin.orders.back',compact('lists','id','all','roles'));
    }

    /**
     * 退款记录
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function refund(Request $request){
        if($request->isMethod('post')){
            $messages = [
                'refund_money.required' => '退款金额不能为空',
                'refund_money.numeric' => '金额格式错误',
            ];
            $validator = Validator::make($request->all(), [
                'refund_money' => 'bail|required|numeric',
            ], $messages);

            if($validator->fails()){

                return $this->response($validator->errors()->first());
            }
            $money_reg = '/^[1-9]\d*|^[1-9]\d*.\d+[1-9]$/';
            if(!preg_match($money_reg, $request->input('refund_money')) || $request->input('refund_money') == '0'){
                return $this->response('金额格式错误', 1);
            }
            
            if($request->input('money_id')){
                $receivables = Refund::find($request->input('money_id'));
                $receivables->refund_money = $request->input('refund_money');
                $receivables->status = 0;
                $receivables->reason = '';

                if($receivables->save()){
                    $orders = Orders::where('id',$request->input('numid'))->first();
                    $finance_id = Admin::where('grade_id', 1)->whereHas('roles', function(Builder $query){$query->where('id', 5);})->value('id'); 
                    $this->createRemind('订单('.$orders->order_num.')退款'.$request->input('refund_money').'元（待审核）', '', 'Order', [$finance_id]);
                    return $this->response('编辑退款成功', 0);
                }else{
                    return $this->response('编辑退款失败', 1);
                }
            }else{
                $receivables = new Refund;
                $receivables->refund_money = $request->input('refund_money');
                $receivables->remark = $request->input('remark');
                $receivables->order_id = $request->input('id');
                $receivables->refund_time = $request->input('refund_time');

                if($receivables->save()){
                    $orders = Orders::where('id',$request->input('id'))->first();
                    $finance_id = Admin::where('grade_id', 1)->whereHas('roles', function(Builder $query){$query->where('id', 5);})->value('id'); 
                    $this->createRemind('订单('.$orders->order_num.')退款'.$request->input('refund_money').'元（待审核）', '', 'Order', [$finance_id]);
                    return $this->response('添加退款成功', 0);
                }else{
                    return $this->response('添加退款失败', 1);
                }
            }
        }

        $id = $request->input('id');
        $lists = Refund::where('order_id',$id)->orderBy('updated_at','desc')->get();
        $admin = auth('admin')->user();
        $roles  = $admin->roles->first()->id;
        return view('admin.orders.refund',compact('lists','id','roles'));
    }

    /**
    * 审核退款
    **/
    public function examine(Request $request){
        if($request->isMethod('post')){
            $status = $this->verify;
            $refund = Refund::find($request->input('id'));
            $refund->status = $request->input('status');
            $refund->reason = $request->input('reason');

            if($refund->save()){
                $orders = Orders::where('id',$refund->order_id)->first();
                $this->createRemind('订单('.$orders->order_num.')退款'.$refund->refund_money.'元（'.$status[$refund->status].'）请查看', '', 'Order', [$orders->user_id]);
                return $this->response('审核退款成功', 0);
            }else{
                return $this->response('审核退款失败', 1);
            }
        }
        $id = $request->input('id');
        return view('admin.orders.examine',compact('id'));
    }

    //订单分析
    public function analysi(Request $request){
        $where = [];
        $admin = [];
        $date = $request->input('date') ? $request->input('date') : '';
        $customer_id = $request->input('customer_id');
        $user_id = $request->input('user_id');
        $id = auth('admin')->user()->id;
        $sale = Admin::where('grade_id', 1)->whereHas('roles', function(Builder $query){$query->where('id',2);})->value('id');
        $admins = Admin::whereHas('roles', function(Builder $query){$query->where('id',1);})->get('id');
        foreach ($admins as $key => $value) {
            $admin[$key] = $value->id;
        }
        if ($customer_id) {
            $where['customer_id'] = $customer_id;
        }
        if ($user_id) {
            $where['user_id'] = $user_id;
        }elseif(!in_array($id,$admin) && $id != $sale){
             $where['user_id'] = $id;
        }

        $start = date('Y-m-01 00:00:00',strtotime($date));
        $end = date('Y-m-t 23:59:59',strtotime($date));

        //订单分析
        if(!empty($date)){
           $orders_count = Orders::where($where)->whereBetween('created_at', [$start, $end])->count();
           $orders_over = Orders::where('process',18)->where($where)->whereBetween('created_at', [$start, $end])->count();
           $orders_nover = Orders::where('process','<>',18)->where($where)->whereBetween('created_at', [$start, $end])->count();
           $orders_allmoney = Orders::where('process','<>',18)->where($where)->whereBetween('created_at', [$start, $end])->get();
           $orders_spend = Orders::where($where)->whereYear('created_at', $date)->whereBetween('created_at', [$start, $end])->get();
        }else{
            $orders_count = Orders::where($where)->count();
            $orders_over = Orders::where('process',18)->where($where)->count();
            $orders_nover = Orders::where('process','<>',18)->where($where)->count();
            $orders_allmoney = Orders::where('process','<>',18)->where($where)->get();
            $orders_spend = Orders::where($where)->get();
        }

        $orders_id=[];
        $receiv=[];
        $allmoney=[];
        foreach ($orders_allmoney as $key => $value) {
            $allmoney[$key] = $value['plannedamt'];
            $orders_id[] = $value['id'];
        }
        $receivables = Receivables::whereIn('order_id',$orders_id)->get('money');//未完成的回款的金额
        foreach ($receivables as $key => $value) {
            $receiv[$key] = $value['money'];

        }
        $receiva= array_sum($receiv);//未完成的回款总金额
        $orders_allmoney = array_sum($allmoney);//未完成的合同总金额
        $noback = $this->amount($orders_allmoney - $receiva);

        //回款分析
        $orders = Orders::where($where)->get();
        $ordersid = [];
        foreach($orders as $val){
            $ordersid[] = $val['id'];
        }
        if(!empty($date)){
            $orderCount = Receivables::whereIn('order_id',$ordersid)->whereBetween('created_at', [$start, $end])->count();//回款次数
            $orderMoneys = Receivables::whereIn('order_id',$ordersid)->whereBetween('created_at', [$start, $end])->get('money');//回款次数
        }else{
            $orderCount = Receivables::whereIn('order_id',$ordersid)->count();//回款次数
            $orderMoneys = Receivables::whereIn('order_id',$ordersid)->get('money');//回款次数
        }
        
        $orderMoney = [];
        foreach($orderMoneys as $val){
            $orderMoney[] = $val['money'];
        }
        $orderMoney = $this->amount(array_sum($orderMoney)); //回款金额
        if(!empty($date)){
            $ordercus = Receivables::whereIn('order_id',$ordersid)->whereBetween('created_at', [$start, $end])->get('order_id');
        }else{
            $ordercus = Receivables::whereIn('order_id',$ordersid)->get('order_id');
        }
        $ordercu = [];
        foreach($ordercus as $val){
            $ordercu[] = $val['order_id'];
        }

        $orderCustomers = Orders::whereIn('id',$ordercu)->get('customer_id');
        $orderCustomer = [];
        foreach($orderCustomers as $val){
            $orderCustomer[] = $val['customer_id'];
        }
        $backCustomer = count(array_unique($orderCustomer)); //回款的客户

        if(!empty($date)){
            $backOrders = Receivables::whereIn('order_id',$ordersid)->whereBetween('created_at', [$start, $end])->get();//回款订单
        }else{
            $backOrders = Receivables::whereIn('order_id',$ordersid)->get();//回款订单
        }
        $backOrder = [];
        foreach($backOrders as $val){
            $backOrder[] = $val['order_id'];
        }
        $backOrder = count(array_unique($backOrder));

        //商务费用总金额
        $spends = [];
        foreach ($orders_spend as $key => $value) {
            $spends[$key] = $value['spend'];
        }
        $spend = $this->amount(array_sum($spends));

        //统计图数据
        $dates = date('t');
        $month = '';

        $num = [];
        for($i = 1; $i <= $dates; $i++){
            $month .= $i.',';
        }
        $monthDays = [];
        $order_money = [];
        $order_count = [];
        $order_num = [];
        $order_moneys = [];
        $firstDay = date('Y-m-01', time());
        $i = 0;
        $lastDay = date('Y-m-d', strtotime("$firstDay +1 month -1 day"));
        while (date('Y-m-d', strtotime("$firstDay +$i days")) <= $lastDay) {
            $monthDays[] = date('Y-m-d', strtotime("$firstDay +$i days"));
            $i++;
        }
        foreach ($monthDays as $key => $value) {
            $order_count[] = Orders::whereDate('created_at',$value)->where($where)->count();
            $order_num[] = Orders::whereDate('created_at',$value)->where($where)->get('plannedamt');
        }
        foreach ($order_num as $key => $value) {
            foreach ($value as $key => $value) {
                $order_moneys[] = $value['plannedamt'];
            }
            $order_money[] = array_sum($order_moneys);
            $order_moneys = [];
        }
        $order_count = implode(',', $order_count);
        $order_money = implode(',', $order_money);

        //获取客户
        $name = auth('admin')->user();
        $roles  = $name->roles->first()->id; 
        $customer = [];
        if($roles == 1){ //如果等于超级管理员显示全部 
            $customers = Customer::where('status',1)->get();
            foreach ($customers as $key => $value) {
                $customer[$value['id']] = $value['company'];
            }
        }elseif($roles == 2 && $name->grade_id == 1){ //如果等于销售主管
            $customers = Customer::where('status',1)->get();
            foreach ($customers as $key => $value) {
                $customer[$value['id']] = $value['company'];
            }
        }else{
            $customers = Customer::where('uid',$name->id)->get();
            foreach ($customers as $key => $value) {
                $customer[$value['id']] = $value['company'];
            }
        } 

        //获取归属人 
        $users = Admin::whereHas('roles', function(Builder $query){$query->where('id',2);})->get();
        foreach ($users as $key => $value) {
            $user[$value['id']] = $value['nickname'] ?: $value['name'];
        }

        return view('admin.orders.analysi',compact('customer','user','orders_count','orders_over','orders_nover','noback','orderCount','orderMoney','backCustomer','backOrder','month','num','order_count','order_money','roles','name','spend'));
    }

    /**
     * 终止业务
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function stop($id, Request $request){
        if($request->isMethod('post') && $request->filled('remark')){
            $msg = '';
            $s = $request->input('status', 0);
            $remarks = $request->input('remark');

            $order = Orders::find($id);
            $order->remarks = $remarks;

            $order->status = $s;
            if($s == 0){
                $msg = $order->order_num . ' 订单已开启';    
            }
            elseif($s == 1){
                $msg = $order->order_num . ' 订单已终止';    
            }
            elseif($s == 2){
                $msg = $order->order_num . ' 订单已完成';       
            }

            if($order->save()){

                return $this->response($msg, 0);
            }

            return $this->response('终止失败', 1);
        }

        $status = [
            '开启',
            '关闭',
            '完成'
        ];
        if(auth('admin')->user()->roles->first()->id == 2){
            unset($status[2]);
        }

        return view('admin.orders.stop',compact('id', 'status'));
    }


    //删除图片
    public function delimage(Request $request)
    {
        $id = $request->input('id');
        $fj = Files::where('id',$id)->first();
        if(Files::destroy($id)){
            @unlink(public_path() .'/'. $fj['link']);
            return $this->response('删除图片成功', 0);
        }else{
            return $this->response('删除图片失败', 1);
        }
    }

    /**
    *获取省市
    *
    */
    public function getcity(Request $request){
        $id = [];
        $city = [];
        //是否评测
        $isReviewGoods = 0;
        //需要评测的业务
        $reviewGoods = ['IDC', 'IRCS', 'CDN', 'ISP'];
        //节点到市的业务
        $isCityGoods = 0;
        $cityGoods = ['IDC', 'IRCS'];
        //业务
        $good = $request->input('good');
        //城市id
        $code = $request->input('code');
        if(!$code && !$good) return $this->response('参数错误', 1);

        if($good){
            if(in_array($good, $reviewGoods)) $isReviewGoods = 1;
            if(in_array($good, $cityGoods)) $isCityGoods = 1;
        }
        //子城市
        if($code){
            foreach ($code as $key => $value) {
                $id[] = $value['value'];
            }

            $citys = Provinces::whereIn('parent_id',$id)->get();
            foreach ($citys as $key => $val) {
                $city[$key]['name'] = $val->name;
                $city[$key]['value'] = $val->id;
            }
        }

        return ['isReviewGoods'=>$isReviewGoods, 'isCityGoods'=>$isCityGoods, 'city'=>$city];
    }

    /**
     * 业务节点列表
     */
    public function process($id){
        $list = GoodProvinces::where('order_id',$id)->where('review','1')->orderBy('id','desc')->paginate(10)->appends($request->all());
        $goods = Goods::all();
        foreach ($goods as $key => $value) {
            $good[$value->id] = $value->name;
        }
        $provinces = Provinces::all();
        foreach ($provinces as $key => $value) {
            $province[$value->id] = $value->name;
        }
        $users = Admin::all();
        foreach ($users as $key => $value) {
            $user[$value->id] = $value;
        }
        $jsprocess = $this->jsprocess;
        $admin = auth('admin')->user();
        $roles  = $admin->roles->first()->id;
        return view('admin.orders.process',compact('list','good','province','jsprocess','user','roles'));
    }

    /**
     * 业务节点编辑
     */
    public function processedit(Request $request){
        if($request->isMethod('post')){
            $lid = $request->input('lid');
            $goodProvince = GoodProvinces::find($lid);
            $goodProvince->process = $request->input('process');
            $goodProvince->content = $request->input('content');
            $goodProvince->user_id = auth('admin')->user()->id;
            if($goodProvince->save()){
                $GoodProvincesRecord = new GoodProvincesRecord;
                $pro = '';
                $process = $this->jsprocess;
                $proce = explode(',', $request->input('process'));
                foreach ($proce as $key => $value) {
                    $pro .= $process[$value].',';
                }
                $GoodProvincesRecord->order_id = $goodProvince->order_id;
                $GoodProvincesRecord->goodprovince_id = $lid;
                $GoodProvincesRecord->user_id = auth('admin')->user()->id;
                $GoodProvincesRecord->content = '业务状态：'.$pro;
                $GoodProvincesRecord->remarks = $request->input('content');
                $GoodProvincesRecord->save();

                return $this->response('编辑业务节点状态成功', 0);
            }else{
                return $this->response('编辑业务节点状态失败', 1);
            }

        }
        $id = $request->input('id');
        $list = GoodProvinces::find($id);

        if($list->good_id == 1){
            $jsprocess = [
                '1' => '待处理',
                '2' => '备案准备材料',
                '3' => '备案提交，评测中',
                '4' => '备案评测通过',
                '5' => '机房准备材料',
                '6' => '机房提交，评测中',
                '7' => '机房评测未通过',
                '8' => '机房评测通过',
                '9' => '信安设备发货',
                '10' => '信安设备上架',
                '12' => '信安提交，评测中',
                '13' => '信安评测未通过，重新评测',
                '14' => '信安评测通过',
                '15' => '已退回',
                '18' => '已完成'
            ];
        }elseif($list->good_id == 2){
            $jsprocess = $this->jsprocess;
        }elseif($list->good_id == 3){
            $jsprocess = [
                '1' => '待处理',
                '2' => '备案准备材料',
                '3' => '备案提交，评测中',
                '4' => '备案评测通过',
                '9' => '信安设备发货',
                '10' => '信安设备上架',
                '11' => '内测',
                '12' => '信安提交，评测中',
                '13' => '信安评测未通过，重新评测',
                '14' => '信安评测通过',
                '15' => '已退回',
                '18' => '已完成'
            ];
        }elseif($list->good_id == 4){
            if($list->network == 1){
                $jsprocess = [
                    '1' => '待处理',
                    '2' => '备案准备材料',
                    '3' => '备案提交，评测中',
                    '4' => '备案评测通过',
                    '9' => '信安设备发货',
                    '10' => '信安设备上架',
                    '12' => '信安提交，评测中',
                    '13' => '信安评测未通过，重新评测',
                    '14' => '信安评测通过',
                    '15' => '已退回',
                    '18' => '已完成'
                ];
            }else{
                $jsprocess = [
                    '1' => '待处理',
                    '2' => '备案准备材料',
                    '3' => '备案提交，评测中',
                    '4' => '备案评测通过',
                    '15' => '已退回',
                    '18' => '已完成'
                ];
            }
            
        }else{
            $jsprocess = $this->jsprocess;
        }
        return view('admin.orders.processedit',compact('jsprocess','id','list'));
    }

    /**
     * 技术部业务节点状态详情
     */
    public function detail($id){
        $list = GoodProvincesRecord::where('goodprovince_id',$id)->get();

        $users = Admin::all();
        foreach ($users as $key => $value) {
            $user[$value->id] = $value->name;
        }
        return view('admin.orders.detail',compact('list','user'));
    }

    /**
     * 业务节点数据
     * @return [type] [description]
     */
    private function doGoodsCity($goods = [], $province = [], $city = [], $review = [], $network = [], $order_id = 0, $user_id = 0){
        $goodsCity = [];
        //评测选择
        $tmpReview = [];
        foreach((array)$review as $key=>$val){
            $tmpVal = explode('|', $val);
            $tmpReview[$tmpVal[0]][] = $tmpVal[1];
        }

        //省市
        foreach($province as $key=>$val){
            $goodsId = $goods[$key];
            $tmpProvince = explode(',', $val);
            //省对应城市
            if(isset($city[$key]) && $city[$key]){
                $tmpCity = explode(',', $city[$key]);
                foreach($tmpCity as $c){
                    $goodsCity[] = [
                        'order_id' => $order_id,
                        'good_id' => $goodsId,
                        'provinces' => $c,
                        'review' => (isset($tmpReview[$goodsId]) && $tmpReview[$goodsId]) ? ($goodsId != '5' ? (in_array($c, $tmpReview[$goodsId]) ? 1 : 0) : 0) : 0,
                        'network' => 0,
                        'user_id' => $user_id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }

                foreach($tmpProvince as $p){
                    $count = Provinces::where('parent_id', $p)->count();
                    if(!$count){
                        $goodsCity[] = [
                            'order_id' => $order_id,
                            'good_id' => $goodsId,
                            'provinces' => $p,
                            'review' => (isset($tmpReview[$goodsId]) && $tmpReview[$goodsId]) ? ($goodsId != '5' ? (in_array($p, $tmpReview[$goodsId]) ? 1 : 0) : 0) : 0,
                            'network' => 0,
                            'user_id' => $user_id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                    }
                }
            }
            else{
                foreach($tmpProvince as $pp){
                    $goodsCity[] = [
                        'order_id' => $order_id,
                        'good_id' => $goodsId,
                        'provinces' => $pp,
                        'review' => (isset($tmpReview[$goodsId]) && $tmpReview[$goodsId]) ? ($goodsId != '5' ? (in_array($pp, $tmpReview[$goodsId]) ? 1 : 0) : 0) : 0,
                        'network' => $network ? ($goodsId == '4' ? (in_array($pp, $network) ? 1 : 0) : 0) : 0,
                        'user_id' => $user_id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }

            }
        }

        return $goodsCity;
    }
}
