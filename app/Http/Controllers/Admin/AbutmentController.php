<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BackendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\Orders;
use App\Models\Abutment;
use App\Models\Goods;
use App\Models\GoodProvinces;
use App\Models\Provinces;
use App\Admin;

//对接订单
class AbutmentController extends BackendController
{
    //对接订单列表
    public function index(Request $request)
    {
        $where = [];
        $customer_name = $request->input('customer_name');
        $date = $request->input('date');
        $starttime = $request->input('starttime');
        $endtime = $request->input('endtime');
        $admin = auth('admin')->user();
        $roles  = $admin->roles->first()->id;

        if ($starttime) {
            $where[] = ['starttime','>=',strtotime($starttime)];
        }
        if ($endtime) {
            $where[] = ['endtime','<=',strtotime($endtime)];
        }

        $lists = Abutment::where($where);
        if($customer_name){
            $customer_id = Customer::where('company','like','%'.$customer_name.'%')->pluck('id')->toArray();
            $lists = $lists->whereIn('customer_id', $customer_id);
        }
        if($request->has('date') && $date != null){
            $year = date('Y', strtotime($date));
            $month = date('m', strtotime($date));
            $lists = $lists->whereYear('created_at', $year)->whereMonth('created_at', $month);
        }
        if(!in_array($roles, [1,4])){
                $lists = $lists->where('user_id', auth('admin')->user()->id);
            }
        $lists = $lists->orderBy('id','desc')->paginate(10)->appends($request->all());
        return view('admin.abutment.index', compact('lists'));
    }

    /**
     * 创建
     * @return [type] [description]
     */
    public function create(Request $request)
    {
        $customer = [];
        $customers = Customer::where('status',1)->whereHas('orders', function($query){
            $query->whereHas('goodProvinces', function($gquery){
                $gquery->where('good_id', 1);
            });
        })->get();
        
        foreach ($customers as $key => $value) {
            $customer[] = [
                'name' => $value->company,
                'value' => $value->id
            ];
        }

        return view('admin.abutment.create', compact('customer'));
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

        $abutment = new Abutment;
        $abutment->customer_id = $request->input('customer_id');
        $abutment->order_id = $request->input('order_id');
        $abutment->good_id = $request->input('good_id');
        $abutment->node_id = $request->input('node_id');
        $abutment->starttime = strtotime($request->input('starttime'));
        $abutment->endtime = strtotime($request->input('endtime'));
        $abutment->remark = $request->input('remark');
        $abutment->user_id = auth('admin')->user()->id;
        
        if($abutment->save()){
            return $this->response('添加对接订单成功', 0);
        }else{
            return $this->response('添加对接订单失败', 1);
        }
    }

    /**
     * 显示
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function show($id){
        $list = Abutment::find($id);
        return view('admin.abutment.show', compact('list'));
    }

    /**
     * 编辑
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id){
        $list = Abutment::find($id);
        //对应订单编号
        $orders = Orders::where('customer_id', $list->customer_id)
            ->whereDoesntHave('abutment', function($query) use($list){
                $query->Where('id', '<>', $list->id);
            })->with('goodProvinces.goods')->get();
        //业务种类
        $goodname = [];
        $goods = GoodProvinces::where('order_id', $list->order_id)->where('good_id', 1)->groupBy('good_id')->pluck('good_id');
        $goodnames = Goods::where('status', 1)->get();
        foreach ($goodnames as $key => $value) {
            $goodname[$value->id] = $value->name;
        }
        //节点名称
        $nodes = GoodProvinces::where('order_id', $list->order_id)->where('good_id', $list->good_id)->select('provinces')->get();
        
        $order = [];
        $good = [];
        $node = [];
        //对应订单编号
        foreach($orders as $key=>$val){
            $tmpgp = '';
            foreach($val->goodProvinces as $gp){
                $tmpgp = $gp->goods->name;
            }

            $order[] = [
                'name' => $val->order_num,
                'value' => $val->id,
                'goods' => '￥' . $val->plannedamt .','. $tmpgp,
                'selected' => ($val->abutment && $list->id == $val->abutment->id) ? true : false
            ];
        }
        //业务种类
        foreach ($goods as $key => $value) {
            $good[] = [
                'name' => $goodname[$value],
                'value' => $value,
                'selected' => ($list->good_id == $value) ? true : false
            ];
        }
        //节点名称
        foreach ($nodes as $key => $value) {
            $node[] = [
                'name' => $value->province->name,
                'value' => $value->provinces,
                'selected' => ($list->node_id == $value->provinces) ? true : false
            ];
        }
        return view('admin.abutment.edit', compact('list', 'order', 'good', 'node'));
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

        $abutment = Abutment::find($id);
        $abutment->order_id = $request->input('order_id');
        $abutment->good_id = $request->input('good_id');
        $abutment->node_id = $request->input('node_id');
        $abutment->starttime = strtotime($request->input('starttime'));
        $abutment->endtime = strtotime($request->input('endtime'));
        $abutment->remark = $request->input('remark');
        
        if($abutment->save()){
            return $this->response('编辑对接订单成功', 0);
        }else{
            return $this->response('编辑对接订单失败', 1);
        }
    }

    /**
     * 删除
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy(Request $request,$id){
        if(!$id) $id = $request->input('id');
        if(!$id) return $this->response('删除对接订单失败', 1);

        if(Abutment::destroy($id)){
            if(is_array($id)){
                foreach ($id as $key => $value) {
                    Abutment::where('id',$value)->delete();
                }
            }else{
                Abutment::where('id',$id)->delete();
            }
            return $this->response('删除对接订单成功', 0);
        }

        return $this->response('删除对接订单失败', 1);
    }

    /**
    *获取业务种类
    *
    */
    public function getgood(Request $request){

        if($request->isMethod('get')){
            $good = [];
            $order = $request->input('order');
            $goodProvinces = GoodProvinces::where('order_id', $order)->where('good_id', 1)->distinct()->select('good_id')->get();
            
            $result = [];
            foreach ($goodProvinces as $key => $value) {
                $result[] = [
                    'name' => $value->goods->name,
                    'value' => $value->good_id
                ];
            }

            return $this->response('', 0, ['data'=>$result]);
        }

        return $this->response('请求错误', 1);
    }

    /**
    *获取订单号和节点
    *
    */
    public function getnode(Request $request){
        
        if($request->isMethod('get')){
            $good = $request->input('good');
            $order_id = $request->input('order_id');
            if($good == '1'){
                $good = [1,4];
            }else{
                $good = [$good];
            }
            $nodes = GoodProvinces::whereIn('order_id',[$order_id])->whereIn('good_id', $good)->distinct()->select('provinces')->get();
            
            $result = [];
            foreach ($nodes as $key => $value) {
                $result[] = [
                    'name' => $value->province->name,
                    'value' => $value->provinces
                ];
            }

            return $this->response('', 0, ['data'=>$result]);
        }

        return $this->response('请求错误', 1);
    }
}
