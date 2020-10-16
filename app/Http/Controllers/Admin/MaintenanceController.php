<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BackendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\Maintenance;
use App\Models\Goods;
use App\Models\Orders;
use App\Models\GoodProvinces;
use App\Models\Provinces;
use App\Admin;

//维保订单
class MaintenanceController extends BackendController
{
    //维保订单列表
    public function index(Request $request)
    {
        $where = [];
        $customer_name = $request->input('customer_name');
        $date = $request->input('date');
        $contract_starttime = $request->input('contract_starttime');
        $contract_endtime = $request->input('contract_endtime');
        $type = $request->input('hktype');
        $userId = $request->input('userId');
        $admin = auth('admin')->user();
        $roles  = $admin->roles->first()->id;

        if ($contract_starttime) {
            $where[] = ['contract_starttime','>=',strtotime($contract_starttime)];
        }
        if ($contract_endtime) {
            $where[] = ['contract_endtime','<=',strtotime($contract_endtime)];
        }

        $lists = Maintenance::where($where);
        switch ($type) {
            case 1:
                //维保订单托管费用
                $lists = $lists->where('deposit_money', '>', 0);
                break;
            case 2:
                //维保订单成本费用
                $lists = $lists->where('cost_money', '>', 0);
                break;
            case 3:
                //维保订单总金额
                $lists = $lists->where('total', '>', 0);
                break;
            default:
                break;
        }
        if($userId){
            $lists = $lists->where('user_id', $userId);
        }else{
            if(Admin::where('grade_id', 1)->whereHas('roles', function($query){$query->where('id', 4);})->value('id') !== auth('admin')->user()->id && $roles !== 1){
                $lists = $lists->where('user_id', auth('admin')->user()->id);
            }
        }
        if($customer_name){
            $customer_id = Customer::where('company','like','%'.$customer_name.'%')->pluck('id')->toArray();
            $lists = $lists->whereIn('customer_id', $customer_id);
        }
        if($request->has('date') && $date != null){
            $year = date('Y', strtotime($date));
            $month = date('m', strtotime($date));
            $lists = $lists->whereYear('created_at', $year)->whereMonth('created_at', $month);
        }

        $lists = $lists->orderBy('id','desc')->paginate(10)->appends($request->all());
        $order = [];
        $good = [];
        $province = [];
        $orders = Orders::select('id', 'order_num')->get();
        foreach ($orders as $key => $value) {
            $order[$value->id] = $value->order_num;
        }
        $goods = Goods::whereIn('id', [1,2,3,6])->get();
        foreach ($goods as $key => $value) {
            $good[$value->id] = $value->name;
        }
        $provinces = Provinces::all();
        foreach ($provinces as $key => $value) {
            $province[$value->id] = $value->name;
        }
        return view('admin.maintenance.index', compact('lists', 'order', 'good', 'province'));
    }

    /**
     * 创建
     * @return [type] [description]
     */
    public function create(Request $request)
    {
        $customer = [];
        $good = [];
        $customers = Customer::where('status',1)->whereHas('orders', function($query){
            $query->whereDoesntHave('maintenance')->whereHas('goodProvinces', function($gquery){
                $gquery->whereIn('good_id', [1,2,3,6]);
            });
        })->get();
        $goods = Goods::where('status',1)->whereIn('id', [1,2,3,6])->get();
        
        foreach ($customers as $key => $value) {
            $customer[] = [
                'name' => $value->company,
                'value' => $value->id
            ];
        }
        foreach ($goods as $key => $value) {
            $good[] = [
                'name' => $value->name,
                'value' => $value->id
            ];
        }
        return view('admin.maintenance.create', compact('customer', 'good'));
    }

    /**
     * 保存
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request){
        $messages = [
            'customer_id.required' => '客户名称不能为空',
            'order_id.required' => '对应订单编号不能为空',
            'good_id.required' => '业务种类不能为空',
            'node_id.required' => '节点不能为空',
        ];
        $validator = Validator::make($request->all(), [
            'customer_id' => 'bail|required',
            'order_id' => 'bail|required',
            'good_id' => 'bail|required',
            'node_id' => 'bail|required',
        ], $messages);

        if($validator->fails()){

            return $this->response($validator->errors()->first());
        }
        $device = [];
        $good_id = $request->input('good_id');
        $node_id = $request->input('node_id');
        $device_number = $request->input('device_number');
        $sn_number = $request->input('sn_number');
        $address = $request->input('address');
        foreach ($good_id as $key => $value) {
            $device[] = [
                'good_id' => $value ?: null,
                'node_id' => $node_id[$key] ?: null,
                'device_number' => $device_number[$key] ?: null,
                'sn_number' => $sn_number[$key] ?: null,
                'address' => $address[$key] ?: null,
            ];
        }

        $maintenance = new Maintenance;
        $maintenance->customer_id = $request->input('customer_id');
        $maintenance->order_id = $request->input('order_id');
        $maintenance->device_msg = serialize($device);
        $maintenance->contract_starttime = strtotime($request->input('contract_starttime'));
        $maintenance->contract_endtime = strtotime($request->input('contract_endtime'));
        $maintenance->xinan_money = $request->input('xinan_money') ?: 0;
        $maintenance->deposit_money = $request->input('deposit_money') ?: 0;
        $maintenance->upgrade_money = $request->input('upgrade_money') ?: 0;
        $maintenance->record_money = $request->input('record_money') ?: 0;
        $maintenance->cost_money = $request->input('cost_money') ?: 0;
        $maintenance->total = $request->input('total') ?: 0;
        $maintenance->contract_send_time = strtotime($request->input('contract_send_time'));
        $maintenance->contract_back_time = strtotime($request->input('contract_back_time'));
        $maintenance->receipt_send_time = strtotime($request->input('receipt_send_time'));
        $maintenance->payment_time = strtotime($request->input('payment_time'));
        $maintenance->remarks = $request->input('remarks');
        $maintenance->user_id = auth('admin')->user()->id;
        
        if($maintenance->save()){
            return $this->response('添加维保订单成功', 0);
        }else{
            return $this->response('添加维保订单失败', 1);
        }
    }

    /**
     * 显示
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function show($id){
        $list = Maintenance::find($id);
        $order = [];
        $good = [];
        $province = [];
        $orders = Orders::select('id', 'order_num')->get();
        foreach ($orders as $key => $value) {
            $order[$value->id] = $value->order_num;
        }
        $goods = Goods::whereIn('id', [1,2,3,6])->get();
        foreach ($goods as $key => $value) {
            $good[$value->id] = $value->name;
        }
        $provinces = Provinces::all();
        foreach ($provinces as $key => $value) {
            $province[$value->id] = $value->name;
        }
        return view('admin.maintenance.show', compact('list', 'order', 'good', 'province'));
    }

    /**
     * 编辑
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id){
        $list = Maintenance::find($id);
        //对应订单编号
        $orders = Orders::where('customer_id', $list->customer_id)
            ->whereDoesntHave('maintenance', function($query) use($list){
                $query->Where('id', '<>', $list->id);
            })->get();
        //业务种类
        $goods = Goods::where('status',1)->whereIn('id', [1,2,3,6])->get();
        //节点名称
        $nodes = GoodProvinces::whereIn('order_id', [$list->order_id])->select('provinces')->get();
        $device = [];
        $device_msg = unserialize($list->device_msg);
        foreach ($device_msg as $key => $value) {
            $device['good_id'][] = $value['good_id'];
            $device['node_id'][] = $value['node_id'];
        }

        $order = [];
        $good = [];
        $good_id = [];
        //对应订单编号
        foreach($orders as $key=>$val){
            $order[] = [
                'name' => $val->order_num,
                'value' => $val->id,
                'selected' => (in_array($val->id, explode(',', $list->order_id))) ? true : false
            ];
        }
        //业务种类
        foreach ($goods as $key => $value) {
            $good_id[] = [
                'name' => $value->name,
                'value' => $value->id
            ];
            foreach ($device['good_id'] as $k => $v) {
                $good[$k][] = [
                    'name' => $value->name,
                    'value' => $value->id,
                    'selected' => ($value->id == $v) ? true : false
                ];
            }
        }
        
        //节点名称
        foreach ($nodes as $key => $value) {
            foreach ($device['node_id'] as $k => $v) {
                $node[$k][] = [
                    'name' => $value->province->name,
                    'value' => $value->provinces,
                    'selected' => ($value->provinces == $v) ? true : false
                ];
            }
        }

        return view('admin.maintenance.edit', compact('list', 'order', 'good_id', 'good', 'node', 'device_msg', 'device'));
    }

    /**
     * 编辑保存
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request,$id){
        $messages = [
            'customer_id.required' => '客户名称不能为空',
            'order_id.required' => '对应订单编号不能为空',
            'good_id.required' => '业务种类不能为空',
            'node_id.required' => '节点不能为空',
        ];
        $validator = Validator::make($request->all(), [
            'customer_id' => 'bail|required',
            'order_id' => 'bail|required',
            'good_id' => 'bail|required',
            'node_id' => 'bail|required',
        ], $messages);

        if($validator->fails()){

            return $this->response($validator->errors()->first());
        }
        $device = [];
        $good_id = $request->input('good_id');
        $node_id = $request->input('node_id');
        $device_number = $request->input('device_number');
        $sn_number = $request->input('sn_number');
        $address = $request->input('address');
        foreach ($good_id as $key => $value) {
            $device[] = [
                'good_id' => $value ?: null,
                'node_id' => $node_id[$key] ?: null,
                'device_number' => $device_number[$key] ?: null,
                'sn_number' => $sn_number[$key] ?: null,
                'address' => $address[$key] ?: null,
            ];
        }

        $maintenance = Maintenance::find($id);
        $maintenance->order_id = $request->input('order_id');
        $maintenance->device_msg = serialize($device);
        $maintenance->contract_starttime = strtotime($request->input('contract_starttime'));
        $maintenance->contract_endtime = strtotime($request->input('contract_endtime'));
        $maintenance->xinan_money = $request->input('xinan_money') ?: 0;
        $maintenance->deposit_money = $request->input('deposit_money') ?: 0;
        $maintenance->upgrade_money = $request->input('upgrade_money') ?: 0;
        $maintenance->record_money = $request->input('record_money') ?: 0;
        $maintenance->cost_money = $request->input('cost_money') ?: 0;
        $maintenance->total = $request->input('total') ?: 0;
        $maintenance->contract_send_time = strtotime($request->input('contract_send_time'));
        $maintenance->contract_back_time = strtotime($request->input('contract_back_time'));
        $maintenance->receipt_send_time = strtotime($request->input('receipt_send_time'));
        $maintenance->payment_time = strtotime($request->input('payment_time'));
        $maintenance->remarks = $request->input('remarks');
        
        if($maintenance->save()){
            return $this->response('编辑维保订单成功', 0);
        }else{
            return $this->response('编辑维保订单失败', 1);
        }
    }

    /**
     * 删除
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy(Request $request,$id){
        if(!$id) $id = $request->input('id');
        if(!$id) return $this->response('删除维保订单失败', 1);

        if(Maintenance::destroy($id)){
            if(is_array($id)){
                foreach ($id as $key => $value) {
                    Maintenance::where('id',$value)->delete();
                }
            }else{
                Maintenance::where('id',$id)->delete();
            }
            return $this->response('删除维保订单成功', 0);
        }

        return $this->response('删除维保订单失败', 1);
    }
}
