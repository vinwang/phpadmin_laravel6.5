<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BackendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\Orders;
use App\Models\Contract;
use App\Models\Files;
use App\Admin;
use App\Events\SystemLog;

//合同管理
class ContractController extends BackendController
{
    //合同列表
    public function index(Request $request)
    {
        $title = $request->input('title');
        $custom_name = $request->input('customer_id');
        $customer_id = '';
        if($custom_name){
            $customer_id = Customer::where('company','like','%'.$request->input('customer_id').'%')->value('id');
        }
        $starttime = $request->input('starttime');
        $endtime = $request->input('endtime');
        $number = $request->input('number');
        $month = $request->input('month');
        $where = [];
        $customer = [];
        if ($title) {
            $where[] = ['title','like','%'.$title.'%'];
        }
        if ($customer_id) {
            $where['customer_id'] = $customer_id;
        }
        if ($starttime) {
            $where[] = ['starttime','>=',strtotime($starttime)];
        }
        if ($endtime) {
            $where[] = ['endtime','<=',strtotime($endtime)];
        }
        
        $lists = Contract::where($where)->where('type',0)
            ->where(function($query){
                $user = auth('admin')->user();
                if(!$user->hasRole(1) && !$user->hasPermissionTo(46)){

                    $query->whereIn('order_id', Orders::where('user_id', $user->id)->pluck('id'));
                }
            })
            ->when($number, function($query, $number){
                return $query->whereHas('order', function($oquery) use($number){
                    $oquery->where('order_num', 'like', '%'.$number.'%');
                });
            })
            ->when($month, function($query, $month){
                $tmpYear = date('Y', strtotime($month));
                $tmpMonth = date('m', strtotime($month));
                return $query->whereYear('created_at', $tmpYear)->whereMonth('created_at', $tmpMonth);
            })
            ->orderBy('id','desc')
            ->paginate(10)->appends($request->all());
        
        return view('admin.contract.index',compact('lists','title','custom_name','starttime','endtime','number'));
    }

    /**
     * 创建
     * @return [type] [description]
     */
    public function create(Request $request)
    {
        $customer = [];
        $type = $request->input('type');
        $customers = Customer::where('status',1);
        if(!$type){
            $customers = $customers->where(function($query){
                $user = auth('admin')->user();
                if(!$user->hasRole(1) && !$user->hasPermissionTo(46)){
                    $query->where('uid', $user->id);
                }
            });
        }
        $customers = $customers->get();
        foreach ($customers as $key => $value) {
            $customer[] = [
                'name' => $value->company,
                'value' => $value->id
            ];
        }

        return view('admin.contract.create',compact('customer','type'));
    }

    /**
     * 保存
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request){
        $messages = [
            'customer.required' => '客户名称不能为空',
            'order.required' => '对应销售订单不能为空',
            'title.required' => '标题不能为空',
        ];
        $validator = Validator::make($request->all(), [
            'customer' => 'bail|required',
            'order' => 'bail|required',
            'title' => 'bail|required',
        ], $messages);

        $type = $request->input('type');
        $request->flash();

        if($validator->fails()){

            return back()->with('msg', $validator->errors()->first())->with('code', 1);
        }
        if($type == null){
            if(Contract::where('customer_id',$request->input('customer'))->where('order_id',$request->input('order'))->count() > 0){
                return back()->with('msg', '该合同已存在,请重新编辑')->with('code', 1);
            }
        }
        

        $contract = new Contract;
        if($type){
            $contract->type = 1;
        }
        $contract->customer_id = $request->input('customer');
        $contract->order_id = $request->input('order');
        $contract->title = $request->input('title');
        $contract->number = $request->input('number');
        $contract->starttime = strtotime($request->input('starttime'));
        $contract->endtime = strtotime($request->input('endtime'));
        $contract->remark = $request->input('remark');
        
        if($contract->save()){
            if($request->hasFile('picture') || $request->hasFile('annex')){
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

                foreach ($picturePath as $key => $value) {
                    $files = new Files;
                    $files->order_id = $request->input('order');
                    $files->contract_id = $contract->id;
                    $files->type = $type ? 0 : 1;
                    $files->link = $value;
                    $files->save();
                }

                if($annex){
                    foreach ($annex as $key => $value) {
                        if(!empty($value)){
                            $extension = $value->getClientOriginalExtension();
                            $fileName = date('YmdHis').mt_rand(100,999).'.'.$extension;
                            $annexfile = $value->storeAs($url_path,$fileName);
                            $annexPath[] = $annexfile;
                        }
                    }

                    foreach ($annexPath as $k => $v) {
                        $filed = new Files;
                        $filed->order_id = $request->input('order');
                        $filed->contract_id = $contract->id;
                        $filed->type = 2;
                        $filed->link = $v;
                        $filed->save();
                    }
                }
            }
            $msg = '添加合同成功'; 
            if($type){
                $msg = '添加资质成功';
            }
            event(new SystemLog($msg));
            return back()->with('code', 0);
        }
        return back()->with('msg', '添加失败')->with('code', 1);
    }

    /**
     * 显示
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function show($id){
        $list = Contract::find($id);
        $picture = Files::where(['contract_id'=>$id,'type'=>1])->get();
        $annex = Files::where(['contract_id'=>$id,'type'=>2])->get();
        $qualified = Files::where(['contract_id'=>$id,'type'=>0])->get();
        return view('admin.contract.show',compact('list','picture','annex','qualified'));
    }

    /**
     * 编辑
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id){
        $list = Contract::find($id);
        $where = [];
        if($list->type == 1){
            $where['type'] = 1;
        }else{
            $where['type'] = 0;
        }

        $orders = Orders::where('customer_id', $list->customer_id)
            ->whereDoesntHave('contracts', function($query) use($where, $list){
                $query->where($where)->Where('id', '<>', $list->id);
            })->with('goodProvinces.goods')->get();

        $order = [];
        foreach($orders as $key=>$val){
            $tmpgp = '';
            foreach($val->goodProvinces as $gp){
                $tmpgp = $gp->goods->name;
            }

            $order[] = [
                'name' => $val->order_num,
                'value' => $val->id,
                'goods' => '￥' . $val->plannedamt .','. $tmpgp,
                'selected' => ($val->contracts && $list->id == $val->contracts->id) ? true : false
            ];
        }

        $imageinfo = Files::where(['contract_id'=>$id,'type'=>1])->get();
        $annexinfo = Files::where(['contract_id'=>$id,'type'=>2])->get();
        $qualifiedinfo = Files::where(['contract_id'=>$id,'type'=>0])->get();

        return view('admin.contract.edit',compact('list', 'imageinfo', 'annexinfo', 'order', 'qualifiedinfo'));
    }

    /**
     * 编辑保存
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request,$id){
        $messages = [
            'order.required' => '对应销售订单不能为空',
            'title.required' => '标题不能为空'
        ];
        $validator = Validator::make($request->all(), [
            'order' => 'bail|required',
            'title' => 'bail|required'
        ], $messages);

        if($validator->fails()){

            return back()->with('msg', $validator->errors()->first())->with('code', 1);
        }
        
        $contract = Contract::find($id);
        $contract->order_id = $request->input('order');
        $contract->title = $request->input('title');
        $contract->number = $request->input('number');
        $contract->starttime = strtotime($request->input('starttime'));
        $contract->endtime = strtotime($request->input('endtime'));
        $contract->remark = $request->input('remark');
       
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
                        $files->order_id = $request->input('order');
                        $files->link = $value;
                        $files->save();
                    }else{
                        $files = new Files;
                        $files->order_id = $request->input('order');
                        $files->contract_id = $id;
                        if(substr($key,0,7) == 'picture'){
                            //图片
                            $files->type = 1;
                        }elseif(substr($key,0,5) == 'annex'){
                            //附件
                            $files->type = 2;
                        }else{
                            //资质
                            $files->type = 0;
                        }
                        $files->link = $value;
                        $files->save();
                    }
                }
            }
            $msg = '编辑合同成功';
            if($request->input('type') == 1){
                $msg = '编辑资质成功';
            }
            event(new SystemLog($msg));
            return back()->with('code', 0);
        }
        return back()->with('msg', '编辑失败')->with('code', 1);
    }

    /**
     * 删除
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy(Request $request,$id){
        if(!$id) $id = $request->input('id');
        if(!$id) return $this->response('删除合同失败', 1);

        $image = [];
        if(is_array($id)){
            $imageinfo = Files::whereIn('contract_id',$id)->get();
        }
        else{
            $imageinfo = Files::where('contract_id',$id)->get();
        }
        if(Contract::destroy($id)){
            if(is_array($id)){
                foreach ($id as $key => $value) {
                    Files::where('contract_id',$value)->delete();
                    foreach ($imageinfo as $key => $value) {
                        $image[$key] = $value['link'];
                    }
                    foreach ($image as $key => $value) {
                        @unlink(public_path() .'/'. $value);
                    }
                }
            }else{
                Files::where('contract_id',$id)->delete();
                foreach ($imageinfo as $key => $value) {
                    $image[$key] = $value['link'];
                }
                foreach ($image as $key => $value) {
                    @unlink(public_path() .'/'. $value);
                }
            }
            return $this->response('删除合同成功', 0);
        }

        return $this->response('删除合同失败', 1);
    }

    /**
    *获取订单号
    *
    */
    public function getorder(Request $request){
        if($request->isMethod('post')){
            $customer = $request->input('customer');
            $type = $request->input('type') ?: 0;
            $order = Orders::where('customer_id', $customer)->select('id', 'order_num', 'plannedamt');
            if(in_array($type, [0,1])){
                $order = $order->whereDoesntHave('contracts', function($query) use ($type){
                    if($type){
                        $query->where('type', 1);
                    }else{
                        $query->where('type', 0);
                    }

                });
            }elseif($type == 2){
                $order = $order->whereDoesntHave('abutment');
            }elseif($type == 3){
                $order = $order->whereDoesntHave('maintenance');
            }
            
            $order = $order->with('goodProvinces.goods')->get();

            $result = [];
            foreach ($order as $key => $value) {
                $tmpgp = '';
                foreach($value->goodProvinces as $gp){
                    $tmpgp = $gp->goods->name;
                }
                $result[] = [
                    'name' => $value->order_num,
                    'value' => $value->id,
                    'goods' => '￥' . $value->plannedamt .','. $tmpgp
                ];
            }

            return $this->response('', 0, ['data'=>$result]);
        }

        return $this->response('请求错误', 1);
    }

    /**
     * 资质列表
     */
    public function qualified(Request $request){
        $title = $request->input('title');
        $custom_name = $request->input('customer_id');
        $customer_id = '';
        if($custom_name){
            $customer_id = Customer::where('company','like','%'.$request->input('customer_id').'%')->value('id');
        }
        $starttime = $request->input('starttime');
        $endtime = $request->input('endtime');
        $number = $request->input('number');
        $where = [];
        $customer = [];
        if ($title) {
            $where[] = ['title','like','%'.$title.'%'];
        }
        if ($customer_id) {
            $where['customer_id'] = $customer_id;
        }
        if ($starttime) {
            $where[] = ['starttime','>=',strtotime($starttime)];
        }
        if ($endtime) {
            $where[] = ['endtime','<=',strtotime($endtime)];
        }
        
        $lists = Contract::where($where)->where('type',1)
        ->when($number, function($query, $number){
                return $query->whereHas('order', function($oquery) use($number){
                    $oquery->where('order_num', 'like', '%'.$number.'%');
                });
            })
        ->orderBy('id','desc')->paginate(10)->appends($request->all());

        return view('admin.contract.qualified',compact('lists','title','custom_name','starttime','endtime','number'));
    }
}
