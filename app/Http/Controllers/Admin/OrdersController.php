<?php
namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Admin\BackendController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
use App\Models\OrdersCost;
use App\Models\Files;
use App\Models\GoodProvinces;
use App\Models\GoodProvincesRecord;
use App\Models\Abutment;
use App\Models\Maintenance;
use App\Events\SystemLog;
use App\Models\SystemLog as Logs;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

//订单管理
class OrdersController extends BackendController
{
    protected $jsprocess = [
        '1' => '待处理',
        '2' => '处理中',
        '3' => '备案提交，评测中',
        '4' => '备案评测通过',
        '5' => '机房准备材料',
        '6' => '机房提交，评测中',
        '7' => '机房评测未通过',
        '8' => '机房评测通过',
        '9' => '信安设备已发货',
        '10' => '信安设备已上架',
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
        '7' => '已退回',
        '18' => '已完成'
    ];

    protected $orderType = [
        '1' => '办证',
        '2' => '变更',
        '3' => '年审'
    ];

    protected $verify = ['待审核','审核通过','审核未通过','退回','已完成'];

    //订单列表
    public function index(Request $request)
    {
        $order_num = $request->input('order_num');
        $order_type = $request->input('order_type');
        $customer_id = $request->input('customer_id');
        $increment = $request->input('increment');
        $licence = $request->input('licence');
        $licence_start = $request->input('licence_start');
        $licence_end = $request->input('licence_end');
        $user_id = $request->input('user_id'); //归属人
        $pprocess = $request->input('process');
        $type = $request->input('hktype');
        $wenan_status = $request->input('wenan_status');
        $jishu_status = $request->input('jishu_status');
        $userId = $request->input('userId'); //技术部操作人
        $search = $request->input('search'); //订单筛选
        $goods = $request->input('goods'); // 业务种类

        $where = [];
        $customer = [];
        if ($order_num) {
            $where[] = ['order_num','like','%'.$order_num.'%'];
        }
        if ($order_type) {
            $where['order_type'] = $order_type;
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
        if ($wenan_status || $wenan_status == '0') {
            $where['waverify'] = $wenan_status;
        }
        if ($jishu_status || $jishu_status == '0') {
            $where['jsverify'] = $jishu_status;
        }
        if($type || $type == '0'){
            $where[] = ['status', '<>', 1];
        }

        //不同部门订单
        $role = $request->input('role');

        $admin = auth('admin')->user();
        $roles  = $admin->roles->first()->id;
        $lists = Orders::where($where);
        if($customer_id){
            if(is_numeric($customer_id)){
                $lists = $lists->where('customer_id', $customer_id);
                $customer_id = Customer::where('id', $customer_id)->value('company');
            }
            else{
                $lists = $lists->whereHas('customer', function($query) use($customer_id){
                    $query->where('company', 'like', '%'.$customer_id.'%');
                });
            }
        }
        if($request->has('analysidate') && $request->input('analysidate') != null){
            $analysidate = $request->input('analysidate');
            $year = date('Y', strtotime($analysidate));
            $month = date('m', strtotime($analysidate));
            $lists = $lists->whereYear('created_at', $year)->whereMonth('created_at', $month);
        }
        
        //技术部操作人
        if($userId){
            $order_id = [];
            $js_put_id = Admin::where('id', $userId)->whereHas('roles', function($query){
                    $query->where('id', 4);
                })->first();
            //流转给技术部主管的订单id
            foreach ($js_put_id->records as $key => $value) {
                $order_id[] = $value->order_id;
            }
            $lists = $lists->whereIn('id', $order_id);
        }

        //业务种类
        if($goods){
            $lists = $lists->whereHas('goodProvinces', function($query) use($goods){
                $query->where('good_id', $goods);
            });
        }

        $roam_id = [];
        if($search == "0" || $search == 1){
            if(in_array($roles, [1,2])){
                //文案部、技术部、财务部主管id
                $roam_ids = Admin::where('grade_id', 1)->whereHas('roles', function($query){
                    $query->whereIn('id', [3,4,5]);
                })->get();
            }
            elseif(in_array($roles, [3,4])){
                //文案部普通员工id
                $roam_ids = Admin::where('grade_id', '<>', 1)->whereHas('roles', function($query) use($roles){
                    $query->where('id', $roles);
                })->get();
            }
            
            //根据每个主管、员工id循环查询出下面的所有流转记录
            foreach ($roam_ids as $key => $value) {
                //根据每个流转记录查询出订单ID
                foreach ($value->records as $k => $v) {
                    $roam_id[] = $v->order_id;
                }
            }
        }

        switch ($search) {
            case "0":
                //未流转
                $lists = $lists->whereNotIn('id', $roam_id);
                break;

            case 1:
                //已流转
                $lists = $lists->whereIn('id', $roam_id);
                break;

            case 2:
                //发货
                $lists->has('receivables')->where('shipped', 1);
                break;
            case 3:
                //未发货
                $lists->doesntHave('receivables')->orWhere('shipped', 0);
                break;
            case 4:
                //有合同
                $lists = $lists->has('contracts');
                break;
            case 5:
                //未收到合同
                $lists = $lists->doesntHave('contracts');
                break;
            case 6:
                //有退款
                $lists = $lists->has('refund');
                break;
            case 7:
                //无退款
                $lists = $lists->doesntHave('refund');
                break;
            case 8:
                //已开票或已收款
                $lists = $lists->where(function($query){
                    $query->where('stages', 'like', '%billing_time";s%')->orWhere('stages', 'like', '%collection_time";s%');
                });
                break;
            case 9:
                //未开票未收款
                $lists = $lists->where(function($query){
                    $query->where('stages', 'not like', '%billing_time";s%')->where('stages', 'not like', '%collection_time";s%');
                });
                break;
            case 10:
                //技术部未填写成本
                $orderCostIds = OrdersCost::where([
                   ['equipment_cost', '=', 0],
                   ['trusteeship_cost', '=', 0],
                   ['software_cost', '=', 0],
                   ['jishu_other_cost', '=', 0],
                ])->pluck('order_id');

                $lists = $lists->whereIn('id', $orderCostIds)->whereNotIn('status', [1,2]);
            default:
                # code...
                break;
        }
        
        switch ($type) {
            case 1:
                //回款的订单
                $lists = $lists->has('receivables');
                break;
            case 2:
                //已完成订单
                $lists = $lists->where('status', 2);
                break;
            case 3:
                //未完成订单
                $lists = $lists->where('status', '<>', 2);
                break;
            case 4:
                //商务费用
                $lists = $lists->whereHas('cost', function($query){
                    $query->where('business_cost', '>', 0);
                });
                break;
            case 5:
                //外包费用
                $lists = $lists->whereHas('cost', function($query){
                    $query->where('outsourcing_cost', '>', 0);
                });
                break;
            case 6:
                //加急费用
                $lists = $lists->whereHas('cost', function($query){
                    $query->where('urgent_cost', '>', 0);
                });
                break;
            case 7:
                //其他成本
                $lists = $lists->whereHas('cost', function($query){
                    $query->where('xiaoshou_other_cost', '>', 0);
                });
                break;
            case 8:
                //设备成本
                $lists = $lists->whereHas('cost', function($query){
                    $query->where('equipment_cost', '>', 0);
                });
                break;
            case 9:
                //托管费用
                $lists = $lists->whereHas('cost', function($query){
                    $query->where('trusteeship_cost', '>', 0);
                });
                break;
            case 10:
                //其他
                $lists = $lists->whereHas('cost', function($query){
                    $query->where('jishu_other_cost', '>', 0);
                });
                break;
            case 11:
                //完成总量
                $lists = $lists->where('jsverify', 4);
                break;
            case 12:
                //成本总额
                $lists = $lists->whereHas('cost', function($query){
                    $query->where('jishu_other_cost', '>', 0)->orWhere('trusteeship_cost', '>', 0)->orWhere('equipment_cost', '>', 0)->orWhere('software_cost', '>', 0);
                });
                break;
            case 13:
                //已开票
                $lists = $lists->where('stages', 'like', '%billing_time";s%');
                break;
            case 14:
                //已收款
                $lists = $lists->where('stages', 'like', '%collection_time";s%');
                break;
            case 15:
                //未收款
                $lists = $lists->where('stages', 'like', '%collection_time";N%');
                break;
            
            default:
                break;
        }

        $lists = $lists->where(function($query) use ($pprocess){
            if($pprocess){
                $query->whereRaw("find_in_set($pprocess,process)");
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
         
        $lists = $lists->with(['orderActionRecord'=>function($query){
            $query->where('content','like','%业务状态%')->orderBy('id','desc');
        }])->orderBy('id','desc')->paginate(10)->appends($request->all());

        $users = Admin::whereHas('roles', function($query){
                    $query->where('id', 2);
                })->get();

        $process = $this->waprocess;
        $orderType = $this->orderType;
        $verify = $this->verify;
        $show = auth('admin')->user()->hasPermissionTo(89);

        //系统配置
        $sysconfig = Cache::get('sysconfig');
        //业务种类
        $goods = Goods::where('status', 1)->get();

        return view('admin.orders.index', compact('lists','roles','users','process','orderType','verify','show','admin', 'role', 'order_num', 'order_type', 'increment', 'licence', 'licence_start', 'licence_end', 'pprocess', 'user_id', 'customer_id', 'wenan_status', 'jishu_status', 'sysconfig', 'goods'));
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
        $tmpUserNames = Admin::where('grade_id', 1)->where('id','<>',1)->where('id','<>',auth('admin')->user()->id)->whereHas('roles', function($query){$query->where('id', '<>', 2);})->get();  
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

        if($validator->fails()){

            return $this->response($validator->errors()->first());
        }

        $money_reg = '/^[0-9]\d*$/';
        if(!preg_match($money_reg, $request->input('plannedamt'))){
            return $this->response('合同金额格式错误', 1);
        }
        if($request->input('business_cost') && !preg_match($money_reg, $request->input('business_cost'))){
            return $this->response('商务费用格式错误', 1);
        }
        if($request->input('outsourcing_cost') && !preg_match($money_reg, $request->input('outsourcing_cost'))){
            return $this->response('外包成本格式错误', 1);
        }
        if($request->input('urgent_cost') && !preg_match($money_reg, $request->input('urgent_cost'))){
            return $this->response('加急费用格式错误', 1);
        }
        if($request->input('xiaoshou_other_cost') && !preg_match($money_reg, $request->input('xiaoshou_other_cost'))){
            return $this->response('其他成本格式错误', 1);
        }
        $goods = $request->input('goods');
        if(!$goods){
            return $this->response('请选择业务种类', 1);
        }
        $citys = $request->input('citys');
        if(!$citys){
            return $this->response('请填写业务节点', 1);
        }
        $stages = $request->input('stages');
        $stages_time = [];
        foreach($stages as $key=>$val){
            $stages_time[] = [
                'stages' => $val,
                'billing_time' => null,
                'collection_time' => null
            ];
        }
        if(empty($stages)){
            return $this->response('分期金额不能为空', 1);
        }

        if(array_sum($stages) !== intval($request->input('plannedamt'))){
            return $this->response('分期总金额与合同金额不符', 1);
        }
       
        if($request->input('user_id') && in_array(Admin::where('grade_id', 1)->whereHas('roles', function($query){$query->where('id', 4);})->value('id'), explode(',', $request->input('user_id')))){
            $customer = Customer::where('id', $request->input('customer'))->first();
            if($customer->address == '' || $customer->tel == '' || $customer->emil == '' || $customer->username == '' || $customer->password == ''){
                return $this->response('客戶信息不全，无法流转', 1);
            }
        }

        \DB::beginTransaction();

        try{
            $User = auth('admin')->user();
            $orders = new Orders; //订单

            $orders->order_num = $this->orderNum(3);
            $orders->customer_id = $request->input('customer');
            $orders->user_id = $User->id;
            $orders->process = 1;
            $orders->plannedamt = $request->input('plannedamt');
            $orders->stages = serialize($stages_time);
            $orders->remarks = $request->input('remarks');
            $orders->shipped = $request->input('shipped', 0);

            if($orders->save()){
                //节点数据
                $goodsCity = $this->doGoodsCity($goods, $citys, $orders, $User->id);
                // if($goodsCity['goodsProvincesDel']) GoodProvinces::destroy($goodsCity['goodsProvincesDel']);

                $goodProvinces = new GoodProvinces;
                $goodProvinces->insert($goodsCity['goodsCity']);

                //cost
                $orders->cost()->create([
                    'business_cost' => request('business_cost', 0),
                    'outsourcing_cost' => request('outsourcing_cost', 0),
                    'urgent_cost' => request('urgent_cost', 0),
                    'xiaoshou_other_cost' => request('xiaoshou_other_cost', 0),
                    'order_id' => $orders->id
                ]);

                if($request->input('process')){
                    $process = $this->waprocess;
                    $pro = $process[$request->input('process')];
                    $action = new OrderActionRecord;
                    $action->order_id = $orders->id;
                    $action->user_id = $User->id;
                    $action->content = '业务状态：'.$pro;
                    $action->save();
                }

                //流转
                $tmpid = Admin::where('grade_id', 1)->whereHas('roles', function($query){$query->where('id', 2);})->value('id');  
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
                $logMsg = '添加订单('.$orders->order_num.')成功, 合同金额为'.$orders->plannedamt.'元, 分期金额分别为'.implode(',', $request->input('stages')).'元';
                if($request->session()->get('citysAdd')) $logMsg .= ', '.$request->session()->get('citysAdd');

            }
        } catch(\Illuminate\Database\QueryException $ex){
            \DB::rollback();
            $msg = '添加失败';
            $cityError = $request->session()->get('cityError');
            if($cityError) $msg = $cityError;

            return $this->response($msg, 1);
        }

        \DB::commit();
        $request->session()->forget(['cityError', 'citysAdd']);
        return $this->response($logMsg, 0);
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

        $contract = Contract::where('order_id',$id)->where('type',0)->first();
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

        return view('admin.orders.show',compact('list','user','distribute','customer','contract','picture','annex','goodProvince','good','province','orderType','show','name','roles','stagesarr'));
    }

    /**
     * 审核
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function verify(Request $request){
        $id = $request->input('id');
        $orders = Orders::find($id);
        if($request->input('jsverify') || $request->input('jsverify') == '0'){
            $orders->jsverify = $request->input('jsverify');
            $orders->js_content = $request->input('content');
        }elseif($request->input('waverify') || $request->input('waverify') == '0'){
            $orders->waverify = $request->input('waverify');
            $orders->wa_content = $request->input('content');
        }

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
            //退回自动删除流转记录
            if($request->input('jsverify') == '3'){
                OrderActionRecord::where('order_id',$id)
                        ->get()
                        ->each(function($OrderActionRecord){
                            $OrderActionRecord->users()->detach(Admin::where('grade_id', 1)->whereHas('roles', function($query){$query->where('id', 4);})->value('id'));
                        });
            }elseif($request->input('waverify') == '3'){
                OrderActionRecord::where('order_id',$id)
                        ->get()
                        ->each(function($OrderActionRecord){
                            $OrderActionRecord->users()->detach(Admin::where('grade_id', 1)->whereHas('roles', function($query){$query->where('id', 3);})->value('id'));
                        });
            }

            //审核流转
            if($request->input('jsverify')){
                $this->createRemind('订单('.$orders->order_num.')审核（技术部'.$verify[$request->input('jsverify')].'）请到订单列表查看', '', 'Order', $orders->user_id);
            }elseif($request->input('waverify')){
                $this->createRemind('订单('.$orders->order_num.')审核（文案部'.$verify[$request->input('waverify')].'）请到订单列表查看', '', 'Order', $orders->user_id);
            }
            
            return $this->response('订单('.$orders->order_num.')审核成功', 0);
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
        // $ldid = Admin::where('grade_id', 1)->whereHas('roles', function($query){$query->where('id', 4)->orWhere('id', 3);})->get('id')->toArray();
        // foreach ($ldid as $key => $value) {
        //     $ldid[$key] = $value['id'];
        // }
        //技术部流转人员
        if($role == 4){
            $tmpUserNames = Admin::where('id','<>',1)->where('id','<>',auth('admin')->user()->id)->whereHas('roles', function($query){$query->where('id',4);})->get();  

            foreach($tmpUserNames as $key=>$val){
                $username[$key]['name'] = ($val->nickname ?: $val->name).'('.$val->roles->first()->name.')';
                $username[$key]['value'] = $val->id;
            }

        }
        //文案部流转人员
        elseif($role == 3){
            $tmpUserNames = Admin::where('id','<>',1)->where('id','<>',auth('admin')->user()->id)->whereHas('roles', function($query){$query->where('id',3);})->get();  

            foreach($tmpUserNames as $key=>$val){
                $username[$key]['name'] = ($val->nickname ?: $val->name).'('.$val->roles->first()->name.')';
                $username[$key]['value'] = $val->id;
            }
        }else{
            $tmpUserNames = Admin::where('grade_id', 1)->where('id','<>',1)->where('id','<>',auth('admin')->user()->id)->whereHas('roles', function($query){$query->where('id', '<>', 2);})->get();  
            foreach($tmpUserNames as $key=>$val){
                $username[$key]['name'] = ($val->nickname ?: $val->name).'('.$val->roles->first()->name.')';
                $username[$key]['value'] = $val->id;
            }
        }

        $stagesarr = unserialize($list['stages']);

        //技术部
        if($role == 4){
            $goodProvinces = $list->goodProvinces->where('review', 1)->groupBy('good_id');
        }
        else{
            $goodProvinces = $list->goodProvinces->groupBy('good_id');
        }

        foreach($goodProvinces as $key=>$tmpGood){
            foreach($tmpGood as $k=>$province){
                $str = $province->province->name;
                if($province->review){
                    $str .= '（评测）';
                }
                if($province->network){
                    $str .= '（含网）';
                }
                $goodProvinces[$key][$k]->citys = $str . "\r\n";
            }
        }

        return view('admin.orders.edit', compact('list', 'process', 'customer', 'good', 'process_id', 'stagesarr', 'goodProvinces', 'user','role', 'username'));
    }

    /**
     * 编辑保存
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $id){
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

                return $this->response($validator->errors()->first());
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

                if($request->has('waverify')){
                    $orders->waverify = $request->input('waverify');
                    $orders->wa_content = $request->input('wacontent');
                }
                if($orders->process == '18'){
                    $orders->waverify = 4;
                }elseif($orders->process == '7'){
                    $orders->waverify = 3;
                }
                
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

                    
                }
            } catch(\Illuminate\Database\QueryException $ex){
                \DB::rollback();

                return $this->response('添加失败', 1);
            }

            \DB::commit();
            return $this->response('编辑订单('.$orders->order_num.')成功', 0);
            
        }
        elseif($roles == 4){
            $orders = Orders::find($id);
            $orders->jsverify = $request->input('jsverify');
            $orders->js_content = $request->input('jscontent');
            $orders->save();
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


            } catch(\Illuminate\Database\QueryException $ex){
                \DB::rollback();

                return $this->response('添加失败', 1);
            }

            \DB::commit();
            return $this->response('编辑订单('.$orders->order_num.')成功', 0);
            
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

                return $this->response($validator->errors()->first());
            }

            $money_reg = '/^[0-9]\d*$/';
            if(!preg_match($money_reg, $request->input('plannedamt'))){
                return $this->response('合同金额格式错误', 1);
            }
            if($request->input('business_cost') && !preg_match($money_reg, $request->input('business_cost'))){
                return $this->response('商务费用格式错误', 1);
            }
            if($request->input('outsourcing_cost') && !preg_match($money_reg, $request->input('outsourcing_cost'))){
                return $this->response('外包成本格式错误', 1);
            }
            if($request->input('urgent_cost') && !preg_match($money_reg, $request->input('urgent_cost'))){
                return $this->response('加急费用格式错误', 1);
            }
            if($request->input('xiaoshou_other_cost') && !preg_match($money_reg, $request->input('xiaoshou_other_cost'))){
                return $this->response('其他成本格式错误', 1);
            }
            $goods = $request->input('goods');
            $citys = $request->input('citys');
            $stages = $request->input('stages');
            $billing_time = $request->input('billing_time');
            $collection_time = $request->input('collection_time');
            $stages_time = [];
            foreach($stages as $key=>$val){
                $stages_time[] = [
                    'stages' => $val,
                    'billing_time' => $billing_time[$key],
                    'collection_time' => $collection_time[$key]
                ];
            }
            if(!$goods){

                return $this->response('请选择业务种类', 1);
            }
            if(!$citys){

                return $this->response('请填写业务节点', 1);
            }
            if(empty($stages)){

                return $this->response('分期金额不能为空', 1);
            }

            if(array_sum($stages) !== intval($request->input('plannedamt'))){
                return $this->response('分期总金额与合同金额不符', 1);
            }

            if($request->input('user_id') && in_array(Admin::where('grade_id', 1)->whereHas('roles', function($query){$query->where('id', 4);})->value('id'), explode(',', $request->input('user_id')))){
                $client = Customer::where('id', $request->input('customer'))->first();
                if($client->address == '' || $client->tel == '' || $client->emil == '' || $client->username == '' || $client->password == ''){
                    return $this->response('客戶信息不全，无法流转', 1);
                }
            }
            
            \DB::beginTransaction();
            try{
                $User = auth('admin')->user();
                $orders = Orders::find($id);
                if(in_array($orders->jsverify, [2,3])){
                    $orders->jsverify = 0;
                    $orders->js_content = '';
                }
                if(in_array($orders->waverify, [2,3])){
                    $orders->waverify = 0;
                    $orders->wa_content = '';
                }
                $orders->customer_id = $request->input('customer');
                $orders->plannedamt = $request->input('plannedamt');
                $orders->stages = serialize($stages_time);
                $orders->remarks = $request->input('comments');
                $orders->shipped = $request->input('shipped', 0);

                if($orders->save()){
                    // GoodProvinces::where('order_id',$id)->delete();
                    //节点数据
                    $goodsCity = $this->doGoodsCity($goods, $citys, $orders, $User->id);
                    if($goodsCity['goodsProvincesDel']) GoodProvinces::destroy($goodsCity['goodsProvincesDel']);

                    foreach($goodsCity['goodsCity'] as $val){
                        GoodProvinces::updateOrCreate(
                            ['order_id'=>$val['order_id'], 'good_id'=>$val['good_id'], 'provinces'=>$val['provinces'], 'user_id'=>$val['user_id']],
                            ['review'=>$val['review'], 'network'=>$val['network']]
                        );
                    }
                    // $goodProvinces = new GoodProvinces;
                    // $goodProvinces->insert($goodsCity);

                    //cost
                    $business_cost = request('business_cost', 0);
                    $outsourcing_cost = request('outsourcing_cost', 0);
                    $urgent_cost = request('urgent_cost', 0);
                    $xiaoshou_other_cost = request('xiaoshou_other_cost', 0);

                    \App\Models\OrdersCost::updateOrCreate(
                        ['order_id'=>$orders->id],
                        [
                            'business_cost'=>$business_cost,
                            'outsourcing_cost'=>$outsourcing_cost,
                            'urgent_cost'=>$urgent_cost,
                            'xiaoshou_other_cost'=>$xiaoshou_other_cost,
                        ]
                    );
                    
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
                    $logMsg = '编辑订单('.$orders->order_num.')成功, 合同金额为'.$orders->plannedamt.'元, 分期金额分别为'.implode(',', $request->input('stages')).'元';
                    if($request->session()->get('citysAdd')) $logMsg .= ', '.$request->session()->get('citysAdd');
                    if($request->session()->get('citysEdit')) $logMsg .= ', '.$request->session()->get('citysEdit');
                    if($request->session()->get('citysDel')) $logMsg .= ', '.$request->session()->get('citysDel');

                }
            } catch(\Illuminate\Database\QueryException $ex){
                \DB::rollback();
                 $msg = '编辑失败';
                $cityError = session('cityError');
                if($cityError) $msg = $cityError;

                return $this->response($msg, 1);
            }

            \DB::commit();
            $request->session()->forget(['cityError', 'citysAdd', 'citysDel', 'citysEdit']);
            return $this->response($logMsg, 0);

        }elseif($roles == 1){
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

                return $this->response($validator->errors()->first());
            }

            $money_reg = '/^[0-9]\d*$/';
            if(!preg_match($money_reg, $request->input('plannedamt'))){
                return $this->response('合同金额格式错误', 1);
            }
            if($request->input('business_cost') && !preg_match($money_reg, $request->input('business_cost'))){
                return $this->response('商务费用格式错误', 1);
            }
            if($request->input('outsourcing_cost') && !preg_match($money_reg, $request->input('outsourcing_cost'))){
                return $this->response('外包成本格式错误', 1);
            }
            if($request->input('urgent_cost') && !preg_match($money_reg, $request->input('urgent_cost'))){
                return $this->response('加急费用格式错误', 1);
            }
            if($request->input('xiaoshou_other_cost') && !preg_match($money_reg, $request->input('xiaoshou_other_cost'))){
                return $this->response('其他成本格式错误', 1);
            }
            $goods = $request->input('goods');
            $citys = $request->input('citys');

            $stages = $request->input('stages');
            $billing_time = $request->input('billing_time');
            $collection_time = $request->input('collection_time');
            $stages_time = [];
            foreach($stages as $key=>$val){
                $stages_time[] = [
                    'stages' => $val,
                    'billing_time' => $billing_time[$key],
                    'collection_time' => $collection_time[$key]
                ];
            }

            if(!$goods){
                return $this->response('请选择业务种类', 1);
            }
            if(!$citys){
                return $this->response('请填写业务节点', 1);
            }
            if(empty($stages)){

                return $this->response('第一期金额不能为空', 1);
            }

            if(array_sum($stages) !== intval($request->input('plannedamt'))){
                return $this->response('分期总金额与合同金额不符', 1);
            }

            if($request->input('user_id') && in_array(Admin::where('grade_id', 1)->whereHas('roles', function($query){$query->where('id', 4);})->value('id'), explode(',', $request->input('user_id')))){
                $client = Customer::where('id', $request->input('customer'))->first();
                if($client->address == '' || $client->tel == '' || $client->emil == '' || $client->username == '' || $client->password == ''){
                    return $this->response('客戶信息不全，无法流转', 1);
                }
            }
            
            \DB::beginTransaction();
            try{
                $User = auth('admin')->user();
                $orders = Orders::find($id);
                if(in_array($orders->jsverify, [2,3])){
                    $orders->jsverify = 0;
                    $orders->js_content = '';
                }
                if(in_array($orders->waverify, [2,3])){
                    $orders->waverify = 0;
                    $orders->wa_content = '';
                }
                $orders->order_type = $request->input('order_type', 1);
                $orders->customer_id = $request->input('customer');
                $orders->increment = $request->input('increment');
                $orders->licence = $request->input('licence');
                $orders->licence_start = strtotime($request->input('licence_start'));
                $orders->licence_end = strtotime($request->input('licence_end'));
                $orders->process = $request->input('process');
                $orders->plannedamt = $request->input('plannedamt');
                $orders->stages = serialize($stages_time);
                $orders->remarks = $request->input('comments');
                $orders->notes = $request->input('remarks');
                $orders->shipped = $request->input('shipped', 0);

                if($orders->save()){
                    //节点数据
                    $goodsCity = $this->doGoodsCity($goods, $citys, $orders, $User->id);
                    if($goodsCity['goodsProvincesDel']) GoodProvinces::destroy($goodsCity['goodsProvincesDel']);
                    foreach($goodsCity['goodsCity'] as $val){
                        GoodProvinces::updateOrCreate(
                            ['order_id'=>$val['order_id'], 'good_id'=>$val['good_id'], 'provinces'=>$val['provinces'], 'user_id'=>$val['user_id']],
                            ['review'=>$val['review'], 'network'=>$val['network']]
                        );
                    }
                    // $goodProvinces = new GoodProvinces;
                    // $goodProvinces->insert($goodsCity);

                    //cost;
                    $business_cost = request('business_cost', 0);
                    $outsourcing_cost = request('outsourcing_cost', 0);
                    $urgent_cost = request('urgent_cost', 0);
                    $xiaoshou_other_cost = request('xiaoshou_other_cost', 0);

                    \App\Models\OrdersCost::updateOrCreate(
                        ['order_id'=>$orders->id],
                        [
                            'business_cost'=>$business_cost,
                            'outsourcing_cost'=>$outsourcing_cost,
                            'urgent_cost'=>$urgent_cost,
                            'xiaoshou_other_cost'=>$xiaoshou_other_cost,
                        ]
                    );

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
                    $logMsg = '编辑订单('.$orders->order_num.')成功, 合同金额为'.$orders->plannedamt.'元, 分期金额分别为'.implode(',', $request->input('stages')).'元';
                    if($request->session()->get('citysAdd')) $logMsg .= ', '.$request->session()->get('citysAdd');
                    if($request->session()->get('citysEdit')) $logMsg .= ', '.$request->session()->get('citysEdit');
                    if($request->session()->get('citysDel')) $logMsg .= ', '.$request->session()->get('citysDel');

                }
            } catch(\Illuminate\Database\QueryException $ex){
                \DB::rollback();
                 $msg = '编辑失败';
                $cityError = session('cityError');
                if($cityError) $msg = $cityError;

                return $this->response($msg, 1);
            }

            \DB::commit();
            $request->session()->forget(['cityError', 'citysAdd', 'citysDel', 'citysEdit']);
            return $this->response($logMsg, 0);
        }

        return $this->response('编辑失败', 1);
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
                        OrdersCost::where('order_id',$value)->delete();
                        Abutment::where('order_id',$value)->delete();
                        Maintenance::where('order_id',$value)->delete();
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
                    OrdersCost::where('order_id',$id)->delete();
                    Abutment::where('order_id',$id)->delete();
                    Maintenance::where('order_id',$id)->delete();
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
        $admin = auth('admin')->user();
        $roles  = $admin->roles->first()->id;

        if($request->isMethod('post')){
            $id = $request->input('id');
            $stages = $request->input('stages');
            $billing_time = $request->input('billing_time');
            $collection_time = $request->input('collection_time');
            $rece_id = $request->input('rece_id');
            $stages_time = [];
            foreach($stages as $key=>$val){
                $stages_time[] = [
                    'stages' => $val,
                    'billing_time' => $billing_time[$key],
                    'collection_time' => $collection_time[$key]
                ];
            }

            $orders = Orders::find($id);
            $orders->stages = serialize($stages_time);
            $orders->content = $request->input('content');

            if($orders->save()){
                $stages_money = 0;
                $isreceivables = 0;
                foreach ($stages as $key => $value) {
                    if($collection_time[$key]){
                        if($rece_id[$key]){
                            $receivables = Receivables::find($rece_id[$key]);
                            $receivables->money = $value;
                            $receivables->back_time = $collection_time[$key];
                            $receivables->remark = $request->input('remark');
                        }
                        else{
                            $receivables = new Receivables;
                            $receivables->money = $value;
                            $receivables->order_id = $id;
                            $receivables->back_time = $collection_time[$key];
                            $receivables->remark = $request->input('remark');
                        }
                        $isreceivables = $receivables->save();
                        $stages_money += $value;
                    }
                    else{
                        if($rece_id[$key]){
                            $isreceivables = Receivables::destroy($rece_id[$key]);
                        }
                    }
                }
                if($isreceivables){
                    $this->createRemind('订单('.$orders->order_num.')已回款'.$stages_money.'元', '', 'Order', [$orders->user_id]);
                    //发货提醒
                    if($orders->shipped && $orders->receivables->count() == 1){
                        $userId = [];
                        $records = $orders->orderActionRecord()->with('users')->get();
            
                        foreach($records as $record){
                            $tmpUsers = $record->users()->whereHas('roles', function($query){
                                $query->where('id', 4);
                            })->get();
                            foreach($tmpUsers as $tmpUser){
                                if(!in_array($tmpUser->id, $userId)) $userId[] = $tmpUser->id;
                            }
                        }
                        
                        $this->createRemind('订单('.$orders->order_num.')已回款，可以发货了', '', 'Order', $userId);
                    }
                }
                return $this->response('订单('.$orders->order_num.')回款成功', 0);
            }
            else{
                return $this->response('添加回款失败', 1);
            }
            
        }

        $id = $request->input('id');
        $orders = Orders::find($id);
        $stages = $orders->stages ? unserialize($orders->stages) : '';

        if($orders->receivables()->count()){
            foreach($stages as $key=>$stage){
                foreach($orders->receivables as $rece){        
                    if($stage['stages'] == $rece->money && $stage['collection_time'] == $rece->back_time){
                        $stages[$key]['rece_id'] = $rece->id;
                    }
                }
            }
        }
        
        return view('admin.orders.back',compact('orders','id','roles','stages', 'admin'));
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
            if(!preg_match($money_reg, $request->input('refund_money'))){
                return $this->response('金额格式错误', 1);
            }
            
            if($request->input('money_id')){
                $number = [];
                $money = [];
                //订单id
                $num_id = $request->input('numid');
                //除去当前要修改的退款id的所有退款
                $numbers = Refund::where('order_id',$num_id)->where('id','<>',$request->input('money_id'))->get();
                //所有回款总金额
                $moneys = Receivables::where('order_id',$num_id)->get('money');
                foreach ($moneys as $key => $value) {
                    $money[$key] = $value->money;
                }
                //回款总金额
                $pmoney = array_sum($money);
                foreach($numbers as $val){
                    $number[$val['id']] = $val['refund_money'];
                }
                //退款总金额
                $sum = array_sum($number);

                $surplus = ($pmoney-$sum)-$request->input('refund_money');
                if($surplus < 0){
                    return $this->response('退款金额已大于可退款金额,请重新填写!', 1);
                }
                $receivables = Refund::find($request->input('money_id'));
                $receivables->refund_money = $request->input('refund_money');
                $receivables->status = 0;
                $receivables->reason = '';

                if($receivables->save()){
                    $orders = Orders::where('id',$request->input('numid'))->first();
                    $finance_id = Admin::where('grade_id', 1)->whereHas('roles', function($query){$query->where('id', 5);})->value('id'); 
                    $this->createRemind('订单('.$orders->order_num.')退款'.$request->input('refund_money').'元（待审核）', '', 'Order', [$finance_id]);
                    return $this->response('订单('.$orders->order_num.')退款成功', 0);
                }else{
                    return $this->response('编辑退款失败', 1);
                }
            }else{
                if(Receivables::where('order_id',$request->input('id'))->count() == 0){
                    return $this->response('暂未收到回款,不可退款', 1);
                }
                if($request->input('refund_money') > $request->input('retire')){
                    return $this->response('退款金额已大于可退款金额,请重新填写', 1);
                }
                $receivables = new Refund;
                $receivables->refund_money = $request->input('refund_money');
                $receivables->remark = $request->input('remark');
                $receivables->order_id = $request->input('id');
                $receivables->refund_time = $request->input('refund_time');

                if($receivables->save()){
                    $orders = Orders::where('id',$request->input('id'))->first();
                    $finance_id = Admin::where('grade_id', 1)->whereHas('roles', function($query){$query->where('id', 5);})->value('id'); 
                    $this->createRemind('订单('.$orders->order_num.')退款'.$request->input('refund_money').'元（待审核）', '', 'Order', [$finance_id]);
                    return $this->response('订单('.$orders->order_num.')退款成功', 0);
                }else{
                    return $this->response('添加退款失败', 1);
                }
            }
        }

        $back = [] ;
        $list = [] ;
        $id = $request->input('id');
        //合同金额
        $plannedamt = $this->amount($request->input('plannedamt'));
        //该订单下的所有回款金额
        $receivables = Receivables::where('order_id',$id)->get('money');
        foreach ($receivables as $key => $value) {
            $back[$key] = $value->money;
        }
        
        $lists = Refund::where('order_id',$id)->orderBy('updated_at','desc')->get();
        foreach ($lists as $key => $value) {
            $list[$key] = $value->refund_money;
        }
        //该订单下的所有退款总和
        $sum = array_sum($list);
        //该订单下的所有回款总和
        $backall = array_sum($back);
        //可退金额
        $receivables_money = $backall-$sum;

        $admin = auth('admin')->user();
        $roles  = $admin->roles->first()->id;

        return view('admin.orders.refund',compact('lists','id','roles','plannedamt','receivables_money'));
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
                return $this->response('审核订单('.$orders->order_num.')退款成功', 0);
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
        $date = $request->input('date', '');
        $customer_id = $request->input('customer_id');
        $user_id = $request->input('user_id');
        $admin = auth('admin')->user();
        $where[] = ['status', '<>', 1];
        if ($customer_id) {
            $where['customer_id'] = $customer_id;
        }
        
        if($admin->hasRole(1) || $admin->grade->id == 1){
             if($user_id){
                $where['user_id'] = $user_id;   
             }
        }
        else{
            $where['user_id'] = $admin->id;    
        }

        $start = '';
        $end = '';
        if($date){
            $start = date('Y-m-01 00:00:00',strtotime($date));
            $end = date('Y-m-t 23:59:59',strtotime($date));    
        }

        //订单分析
        //订单总数
        $orders = Orders::where($where)->where(function($query) use($date, $start, $end){
            if($date){
                $query->whereBetween('created_at', [$start, $end]);
            }
        })->select('id', 'stages', 'status', 'plannedamt')->get()->toArray();

        $ordersCount = $orders ? count($orders) : 0;
        $ordersId = $orders ? Arr::pluck($orders, 'id') : [];
        $ordersStages = $orders ? Arr::pluck($orders, 'stages') : [];

        //已完成订单
        $ordersFinishedCount = $ordersUnfinishedCount = $ordersAmount = 0;
        foreach($orders as $val){
            if($val['status'] == 2){
                $ordersFinishedCount++;
            }
            elseif($val['status'] != 2){
                $ordersUnfinishedCount++;
            }
            $ordersAmount += $val['plannedamt'];
        }

        //开票收款统计
        $kpamount = 0;
        $skamount = 0;
        $wskamount = 0;
        foreach($ordersStages as $tmpStages){
            $stages = unserialize($tmpStages);
            foreach($stages as $val){
                if(isset($val['billing_time']) && $val['billing_time']){
                    $kpamount += $val['stages'];
                }

                if(isset($val['collection_time']) && $val['collection_time']){
                    $skamount += $val['stages'];
                }
            }
        }

        $wskamount = $ordersAmount - $skamount;

        //成本
        $cost = OrdersCost::whereIn('order_id', $ordersId)
                    ->selectRaw('sum(business_cost) as businessCost, sum(outsourcing_cost) as outsourcingCost, sum(urgent_cost) as urgentCost, sum(xiaoshou_other_cost) as xiaoshouOtherCost, sum(equipment_cost) as equipmentCost, sum(trusteeship_cost) as trusteeshipCost, sum(jishu_other_cost) as jishuOtherCost, sum(software_cost) as softwareCost')
                    ->first();

        //回款分析
        //回款次数
        $receivablesCount = Receivables::whereIn('order_id', $ordersId)->count();
        //回款金额
        $receivables = Receivables::whereIn('order_id', $ordersId)->sum('money');
        //退款金额
        $refundAmount = Refund::whereIn('order_id', $ordersId)->sum('refund_money');
        $receivables = $receivables - $refundAmount;
        $skamount = $skamount - $refundAmount;

        //回款的客户
        $receivablesCustomers = Customer::where(function($query) use($ordersId){
            $query->whereHas('orders', function($oquery) use($ordersId){
                $oquery->whereIn('id', $ordersId)->has('receivables');
            });
        })->count();
        //回款的订单
        $receivablesOrdersCount = Orders::whereIn('id', $ordersId)->has('receivables')->count();

        //统计图数据
        $dates = date('t');
        $month = '';

        $num = [];
        for($i = 1; $i <= $dates; $i++){
            $month .= $i.',';
        }
        $orderLastCount = [];
        $orderLastAmount = [];
        $order_money = '';
        $order_count = '';
        $firstDay = date('Y-m-01');
        $i = 0;
        $lastDay = date('Y-m-d', strtotime("$firstDay +1 month -1 day"));

        $allOrders = Orders::where($where)->select(['plannedamt', 'created_at'])->get()->toArray();

        $monthDay = date('Y-m-d', strtotime("$firstDay +$i days"));
        while ($monthDay <= $lastDay) {
            $monthDay = date('Y-m-d', strtotime("$firstDay +$i days"));
            $tmpCount = 0;
            $tmpAmount = 0;
            foreach($allOrders as $tmpOrder){
                if(date('Y-m-d', strtotime($tmpOrder['created_at'])) == $monthDay){
                    $tmpCount++;
                    $tmpAmount += $tmpOrder['plannedamt'];
                }
            }
            $orderLastCount[] = $tmpCount;
            $orderLastAmount[] = $tmpAmount;
            $i++;
        }
        $order_count = implode(',', $orderLastCount);
        $order_money = implode(',', $orderLastAmount);

        //获取客户
        $adminRole  = $admin->roles->first()->id;
        if($adminRole == 1 || ($adminRole == 2 && $admin->grade_id == 1)){
            $customers = Customer::where('status', 1)->select(['id', 'company'])->get();
        }
        else{
            $customers = Customer::where('uid', $admin->id)->where('status', 1)->select(['id', 'company'])->get();
        } 

        //获取归属人 
        $users = Admin::whereHas('roles', function($query){$query->where('id',2);})->select(['id', 'name', 'nickname'])->get();

        return view('admin.orders.analysi', compact('customers', 'users', 'ordersCount', 'ordersFinishedCount', 'ordersUnfinishedCount', 'ordersAmount', 'receivablesCount', 'receivables', 'receivablesCustomers', 'receivablesOrdersCount', 'month', 'num', 'order_count', 'order_money', 'adminRole', 'admin', 'cost', 'kpamount', 'skamount', 'wskamount'));
    }

    /**
     * 终止业务
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function stop($id, Request $request){
        if($request->isMethod('post')){
            $msg = '';
            $s = $request->input('status', 0);
            $remarks = $request->input('remark');

            $order = Orders::find($id);
            if($remarks){
                $order->remarks = $remarks;
            }
            $order->status = $s;
            if($s == 0){
                $msg = $order->order_num . ' 订单已开启';    
            }
            elseif($s == 1){
                $msg = $order->order_num . ' 订单已终止';    
            }
            elseif($s == 2){
                $msg = $order->order_num . ' 订单已完成';  
                if($order->jsverify != 4 || $order->waverify != 4){
                    // return $this->response('部门还有任务未完成，暂不能完成订单', 1);
                    // $ret = OrderActionRecord::where('order_id',$id)->with(['users'=>function($query){}])->get();dd($ret);
                }     
            }
            elseif($s == 3){
                $msg = $order->order_num . ' 订单待审核';       
            }
            elseif($s == 4){
                $msg = $order->order_num . ' 订单审核通过';       
            }
            elseif($s == 5){
                $msg = $order->order_num . ' 订单审核未通过';       
            }

            if($order->save()){
                $finance_id = Admin::where('grade_id', 1)->whereHas('roles', function($query){$query->where('id', 5);})->value('id'); 
                if($s == 3){
                    $this->createRemind('订单('.$order->order_num.')终止业务（待审核）', '', 'Order', [$finance_id]);
                }elseif($s == 4){
                    $this->createRemind('订单('.$order->order_num.')终止业务（审核通过）', '', 'Order', [$order->user_id]);
                }elseif($s == 5){
                    $this->createRemind('订单('.$order->order_num.')终止业务（审核未通过）', '', 'Order', [$order->user_id]);
                }
                return $this->response($msg, 0);
            }

            return $this->response('终止失败', 1);
        }
        $role = auth('admin')->user()->roles->first()->id;
        $status = [
            0 => '开启',
            1 => '关闭',
            2 => '完成'
        ];
        $state = [];
        if($role == 2){
            $state = [
                3 => '待审核'
            ];
            unset($status[2]);
        }elseif($role == 5){
            $state = [
                4 => '审核通过',
                5 => '审核未通过',
                2 => '完成'
            ];
        }
        $orderstatus = Orders::find($id);

        return view('admin.orders.stop',compact('id', 'status', 'state', 'role', 'orderstatus'));
    }

    /**
     * 技术部成本
     * @return [type] [description]
     */
    public function jscost($id, Request $request){
        $order = Orders::find($id);
        if($request->isMethod('post') && $order){
            $equipment_cost = $request->input('equipment_cost') ?: 0;
            $trusteeship_cost = $request->input('trusteeship_cost') ?: 0;
            $jishu_other_cost = $request->input('jishu_other_cost') ?: 0;
            $software_cost = $request->input('software_cost') ?: 0;
            
            $result = \App\Models\OrdersCost::updateOrCreate(
                ['order_id'=>$id],
                [
                    'equipment_cost' => $equipment_cost,
                    'trusteeship_cost' => $trusteeship_cost,
                    'software_cost' => $software_cost,
                    'jishu_other_cost' => $jishu_other_cost,
                    'jishu_remarks' => $request->input('jishu_remarks')
                ]
            );

            if($result){
                return $this->response('订单('.$order->order_num.')技术部成本编辑成功', 0);
            }

            return $this->response('技术部成本编辑失败', 1);
        }

        $cost = $order->cost;

        return view('admin.orders.jscost', compact('id', 'cost'));
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
        $list = GoodProvinces::with(['goodProvincesRecord'=>function($query){
            $query->orderBy('id','desc');
        }])->where('order_id',$id)->where('review','1')->orderBy('id','desc')->paginate(10);
       
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
            if($goodProvince->save()){
                $GoodProvincesRecord = new GoodProvincesRecord;
                $pro = '';
                $code = '';
                $process = $this->jsprocess;
                $proce = explode(',', $request->input('process'));
                foreach ($proce as $key => $value) {
                    $pro .= $process[$value].',';
                    if(in_array($value, [4,9])){
                        $code = 1;
                    }
                }
                $GoodProvincesRecord->order_id = $goodProvince->order_id;
                $GoodProvincesRecord->goodprovince_id = $lid;
                $GoodProvincesRecord->user_id = auth('admin')->user()->id;
                $GoodProvincesRecord->content = '业务状态：'.$pro;
                $GoodProvincesRecord->remarks = $request->input('content');
                $GoodProvincesRecord->save();

                
                if($code){
                    $device = [];
                    $device_msg = [];
                    $qcustomer = '';
                    $customer_id = Orders::where('id', $goodProvince->order_id)->value('customer_id');
                    $customer = Maintenance::where('customer_id', $customer_id)->value('order_id');
                    if($customer){
                        if(!in_array($goodProvince->order_id, [$customer])){
                            $qcustomer = $customer.','.$goodProvince->order_id;
                        }else{
                            $qcustomer = $customer;
                        }
                        $device = unserialize(Maintenance::where('customer_id', $customer_id)->value('device_msg'));
                        foreach ($device as $key => $value) {
                            $device_msg['good_id'][] = $value['good_id'];
                            $device_msg['node_id'][] = $value['node_id'];
                        }
                        if(!in_array(($goodProvince->good_id == 4 ? 1 : $goodProvince->good_id), $device_msg['good_id']) && !in_array($goodProvince->provinces, $device_msg['node_id'])){
                            $device[] = [
                                'good_id' => $goodProvince->good_id == 4 ? 1 : $goodProvince->good_id,
                                'node_id' => $goodProvince->provinces,
                                'device_number' => null,
                                'sn_number' => null,
                                'address' => null,
                            ];
                        }elseif(!in_array(($goodProvince->good_id == 4 ? 1 : $goodProvince->good_id), $device_msg['good_id']) && in_array($goodProvince->provinces, $device_msg['node_id'])){
                            $device[] = [
                                'good_id' => $goodProvince->good_id == 4 ? 1 : $goodProvince->good_id,
                                'node_id' => $goodProvince->provinces,
                                'device_number' => null,
                                'sn_number' => null,
                                'address' => null,
                            ];
                        }elseif(in_array(($goodProvince->good_id == 4 ? 1 : $goodProvince->good_id), $device_msg['good_id']) && !in_array($goodProvince->provinces, $device_msg['node_id'])){
                            $device[] = [
                                'good_id' => $goodProvince->good_id == 4 ? 1 : $goodProvince->good_id,
                                'node_id' => $goodProvince->provinces,
                                'device_number' => null,
                                'sn_number' => null,
                                'address' => null,
                            ];
                        }
                    }else{
                        $qcustomer = $goodProvince->order_id;
                        $device[] = [
                            'good_id' => $goodProvince->good_id == 4 ? 1 : $goodProvince->good_id,
                            'node_id' => $goodProvince->provinces,
                            'device_number' => null,
                            'sn_number' => null,
                            'address' => null,
                        ];
                    }
                   
                    Maintenance::updateOrCreate(
                        ['customer_id' => $customer_id],
                        ['order_id' => $qcustomer, 'device_msg' => serialize($device), 'user_id' => auth('admin')->user()->id]
                    );
                }

                //退回的节点流转
                $orders = Orders::where('id',$goodProvince->order_id)->first();

                if($orders->goodProvinces()->where('process', '15')->where('review', 1)->first() != null){
                    $orders->jsverify = 3;
                    $orders->save();
                }elseif($orders->goodProvinces()->where('process', '<>', '18')->where('review', 1)->first() == null){
                    $orders->jsverify = 4;
                    $orders->save();
                }else{
                    $orders->jsverify = 1;
                    $orders->save();
                }

                if($goodProvince->process == '15'){
                    $this->createRemind('有订单('.$orders->order_num.')已流转到您，业务节点（已退回），请到订单列表查看', '', 'Order', $orders->user_id);
                }

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
                '2' => '处理中',
                '3' => '备案提交，评测中',
                '4' => '备案评测通过',
                '5' => '机房准备材料',
                '6' => '机房提交，评测中',
                '7' => '机房评测未通过',
                '8' => '机房评测通过',
                '9' => '信安设备已发货',
                '10' => '信安设备已上架',
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
                '2' => '处理中',
                '3' => '备案提交，评测中',
                '4' => '备案评测通过',
                '9' => '信安设备已发货',
                '10' => '信安设备已上架',
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
                    '2' => '处理中',
                    '3' => '备案提交，评测中',
                    '4' => '备案评测通过',
                    '9' => '信安设备已发货',
                    '10' => '信安设备已上架',
                    '12' => '信安提交，评测中',
                    '13' => '信安评测未通过，重新评测',
                    '14' => '信安评测通过',
                    '15' => '已退回',
                    '18' => '已完成'
                ];
            }else{
                $jsprocess = [
                    '1' => '待处理',
                    '2' => '处理中',
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
     * 订单编辑操作日志
     * @return [type] [description]
     */
    public function logs(Request $request){
        if($request->method('post') && $request->has('id')){
            $read = $request->input('read', 1);

            $log = Logs::find($request->input('id'));
            $log->read = $read;
            if($log->save()){

                return $this->response('', 0);
            }

            return $this->response('参数错误', 1);
        }

        $comment = $request->input('comment');
        $user_id = $request->input('user_id');
        $starttime = $request->input('starttime');
        $endtime = $request->input('endtime');
        $where = [];
        if ($comment) {
            $where[] = ['comment','like','%'.$comment.'%'];
        }
        if ($user_id) {
            $where['user_id'] = $user_id;
        }
        if ($starttime) {
            $where[] = ['updated_at','>=',$starttime];
        }
        if ($endtime) {
            $where[] = ['updated_at','<=',$endtime];
        }

        $lists = Logs::where($where)
            ->where(function($query){
                $query->where('comment', 'like', '%编辑订单%')->where('comment', 'like', '%业务节点%');
            })
            ->whereHas('user', function($query){
                $query->where('status', 1);
            })
            ->where(function($query){
                $user = auth('admin')->user();
                if(!$user->hasRole(1) && !$user->hasPermissionTo(46) && $user->grade->id == 2){

                    $query->where('user_id', $user->id);
                }
            })
            ->orderBy('id','desc')
            ->paginate(10)->appends($request->all());

        $users = Admin::where('status', 1)->get();

        $adminRole = auth('admin')->user()->roles()->first()->id;

        return view('admin.orders.logs',compact('lists','users','comment','user_id','starttime','endtime', 'adminRole'));
    }

    /**
     * 业务节点数据
     * @return [type] [description]
     */
    private function doGoodsCity($goods = [], $citys = [], $order = [], $user_id = 0){
        $goodsCity = [];
        $error = '';
        $order_id = $order->id;
        $tmpGoodsName = Goods::whereIn('id', $goods)->select('id', 'name')->get()->toArray();
        $goodsNameArray = Arr::pluck($tmpGoodsName, 'name', 'id');
        // $logMsg = '业务节点信息：';
        //删除的节点
        $citysDel = '';
        $tmpGoods = $tmpCitysAdd = $tmpCitysEdit = $goodsProvincesDel = [];

        foreach($citys as $key=>$val){
            $citysAdd = '';
            $citysEdit = '';
            $goodsId = $goods[$key];
            $goodsName = $goodsNameArray[$goodsId];

            // $logMsg .= $goodsName . '<';

            $tmpCity = explode("\r\n", $val);
            foreach($tmpCity as $k=>$tmp){
                $city = \Str::before($tmp, '（');
                $cityId = Provinces::where('name', 'like', trim($city) . '%')->value('id');
                $review = stripos($tmp, '评测') !== false ? 1 : 0;
                $network = $goodsId == 4 ? (stripos($tmp, '含网') !== false ? 1 : 0) : 0;
                if(!$cityId){
                    $error .= $city . ',';
                }
                /*$logMsg .= $city;
                if(stripos($tmp, '评测')){
                    $logMsg .= '(评测)';
                }
                if($goodsId == 4 && stripos($tmp, '含网')){
                    $logMsg .= '(含网)';
                }
                $logMsg .= ',';*/

                //新增节点
                $count = $order->goodProvinces()->where([
                    ['good_id', '=', $goodsId],
                    ['provinces', '=', $cityId]
                ])->first();
                if(!$count){
                    $citysAdd .= $city;
                    if($review){
                        $citysAdd .= '(评测)';
                    }
                    if($network){
                        $citysAdd .= '(含网)';
                    }
                    $citysAdd .= ',';
                }
                //编辑节点
                elseif($count->review != $review || $count->network != $network){
                    $citysEdit .= $city;
                    if($count->review != $review){
                        $citysEdit = $review ? $citysEdit . '(改为评测)' : $citysEdit . '(改为不评测)';
                    }
                    if($count->network != $network){
                        $citysEdit = $network ? $citysEdit . '(改为含网)' : $citysEdit . '(改为不含网)';
                    }
                }

                $tmpGoods[$goodsId][] = $cityId;

                $goodsCity[] = [
                    'order_id' => $order_id,
                    'good_id' => $goodsId,
                    'provinces' => $cityId,
                    'review' => $review,
                    'network' => $network,
                    'user_id' => $user_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }

            if($citysAdd){
                //新增业务
                $tmpCitysAdd[$goodsId] = $citysAdd;   
            }
            if($citysEdit){
                //新增业务
                $tmpCitysEdit[$goodsId] = $citysEdit;   
            }
            // $logMsg .= '> ';
        }

        $citysAdd = '';
        if($tmpCitysAdd){
            foreach($tmpCitysAdd as $key=>$val){
                if(stripos($citysAdd, $goodsNameArray[$key]) === false){
                    $citysAdd .= $goodsNameArray[$key] . $val;
                }
            }
            $citysAdd = '新增的业务节点信息：<font style="color:red">' . $citysAdd .'</font>';
        }
        $citysEdit = '';
        if($tmpCitysEdit){
            foreach($tmpCitysEdit as $key=>$val){
                if(stripos($citysEdit, $goodsNameArray[$key]) === false){
                    $citysEdit .= $goodsNameArray[$key] . $val;
                }
            }
            $citysEdit = '更新的业务节点信息：<font style="color:red">' . $citysEdit .'</font>';
        }

        //删除的业务
        foreach($order->goodProvinces as $kk=>$value){
            
            if(!isset($goodsNameArray[$value->good_id])){
                $goodsName = Goods::where('id', $value->good_id)->value('name');
            }
            else{
                $goodsName = $goodsNameArray[$value->good_id];
            }

            if(!isset($tmpGoods[$value->good_id]) || !in_array($value->provinces, $tmpGoods[$value->good_id])){
                if(stripos($citysDel, $goodsName) === false){
                    $citysDel .= $goodsName;
                }
                $city = Provinces::where('id', $value->provinces)->value('name');

                $citysDel .= $city;
                if($value->review){
                    $citysDel .= '(评测)';
                }
                if($value->network){
                    $citysDel .= '(含网)';
                }
                $citysDel .= ',';

                $goodsProvincesDel[] = $value->id;
            }

        }
        if($citysDel){
            $citysDel = '删除的业务节点信息：<font style="color:red">' . $citysDel .'</font>';
        }

        if($error) session(['cityError'=> $error . '不存在，请检查一下']);
        session(['citysAdd'=> $citysAdd, 'citysDel'=>$citysDel, 'citysEdit'=>$citysEdit]);

        return ['goodsCity' => $goodsCity, 'goodsProvincesDel'=>$goodsProvincesDel];
    }

    /**
     * 随机字符
     * @param  [type] $length [description]
     * @return [type]         [description]
     */
    private function orderNum($length){
        do{
            $token = "";
            $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
            $codeAlphabet.= "0123456789";
            $max = strlen($codeAlphabet); // edited

            for ($i = 0; $i < $length; $i++) {
                $token .= $codeAlphabet[$this->cryptoRandSecure(0, $max-1)];
            }
            $token = 'D'.date('ymd').$token;

            $order = Orders::where('order_num', $token)->first();
        }
        while(!empty($order));

        return $token;
    }
}
