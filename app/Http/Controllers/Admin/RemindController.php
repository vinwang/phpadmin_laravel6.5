<?php
namespace App\Http\Controllers\Admin;

use Log;
use Validator;
use Carbon\Carbon;
use App\Admin;
use App\Models\Remind;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Events\CreateRemind;
use App\Http\Controllers\Admin\BackendController;

class RemindController extends BackendController{

	//提醒周期
	protected $repeat = ['不重复', '每天', '每周', '每月', '每年'];
	//提醒状态
	protected $status = [0=>'未提醒', 1=>'已完成', 2=>'已过期'];

	public function index(Request $request){
		$start = $request->input('start');
		$end = $request->input('end');
		$keywords = $request->input('keywords');

		$list = Remind::where('content', 'like', '%'.$keywords.'%')
		->where(function($query) use($start, $end){
			if($start){
				$query = $query->where('remind_time', '>=', $start);	
			}
			if($end){
				$query->where('remind_time', '<=', $end);
			}
		})
		->where('model', 'Remind')
		->orderBy('created_at', 'desc')->paginate(10)->appends($request->all());

		/*foreach($list as $key=>$val){
			$time = Carbon::parse($val->remind_time);
			$list[$key]->remind_time = Carbon::now()->diffForHumans($time);
		}*/
		
		return view('admin.remind.index', compact('list','start','end','keywords'));
	}

	/**
	 * 创建
	 * @return [type] [description]
	 */
	public function create(){
		$repeat = [];
		$users = [];
		$customers = [];
		foreach($this->repeat as $key=>$val){
			$repeat[$key]['name'] = $val;
			$repeat[$key]['value'] = $key;
		}
		
		$tmpUsers = Admin::all();
		foreach($tmpUsers as $key=>$val){
			$users[$key]['name'] = $val->nickname ?: $val->name;
			$users[$key]['value'] = $val->id;
		}
		$tmpCustomers = Customer::all();
		foreach($tmpCustomers as $key=>$val){
			$customers[$key]['name'] = $val->company;
			$customers[$key]['value'] = $val->id;
		}

		return view('admin.remind.create', compact('users', 'repeat', 'customers'));
	}

	/**
	 * 保存
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function store(Request $request){
		$messages = [
			'required' => '数据不能为空',
		];
		$validator = Validator::make($request->all(), [
			'content' => 'bail|required',
			'remind_time' => 'bail|required',
			'user' => 'bail|required',
			// 'repeat' => 'bail|required',
			'customer' => 'bail|required',
		], $messages);

		if($validator->fails()){

			return $this->response($validator->errors()->first());
		}

		$remind = new Remind;
		$remind->content = $request->input('content');
		$remind->remind_time = $request->input('remind_time');
		$remind->repeat = $request->input('repeat', 0);
		$remind->admin_id = auth('admin')->user()->id;
		$remind->model = 'Remind';

		if($remind->save()){
			$users = explode(',', $request->input('user'));
			$customers = explode(',', $request->input('customer'));
			$remind->users()->attach($users);
			$remind->customers()->attach($customers);

			event(new CreateRemind($remind));
			
			return $this->response('添加回访提醒成功', 0);
		}
		return $this->response('添加回访提醒失败');
	}

	/**
	 * 显示
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function show($id){
		$repeat = [];
		$users = [];
		$customers = [];
		$remind = Remind::find($id);
		foreach($this->repeat as $key=>$val){
			$repeat[$key]['name'] = $val;
			$repeat[$key]['value'] = $key;
			$repeat[$key]['selected'] = $remind['repeat'] == $key ? true : false;
		}
		
		$tmpUsers = Admin::all();
		foreach($tmpUsers as $key=>$val){
			$users[$key]['name'] = $val->nickname ?: $val->name;
			$users[$key]['value'] = $val->id;

			$users[$key]['selected'] = $remind->users->contains($val->id) ? true : false;
		}
		$tmpCustomers = Customer::all();
		foreach($tmpCustomers as $key=>$val){
			$customers[$key]['name'] = $val->company;
			$customers[$key]['value'] = $val->id;
			$customers[$key]['selected'] = $remind->customers->contains($val->id) ? true : false;
		}

		// event(new CreateRemind($remind));

		//更改状态
		if($remind->status == 0){
			$remind->status = 1;
			$remind->save();
		}

		return view('admin.remind.show', compact('remind', 'repeat', 'users', 'customers'));
	}

	/**
	 * 编辑
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function edit($id){
		$repeat = [];
		$users = [];
		$customers = [];
		$remind = Remind::find($id);
		foreach($this->repeat as $key=>$val){
			$repeat[$key]['name'] = $val;
			$repeat[$key]['value'] = $key;
			$repeat[$key]['selected'] = $remind['repeat'] == $key ? true : false;
		}
		
		$tmpUsers = Admin::all();
		foreach($tmpUsers as $key=>$val){
			$users[$key]['name'] = $val->nickname ?: $val->name;
			$users[$key]['value'] = $val->id;

			$users[$key]['selected'] = $remind->users->contains($val->id) ? true : false;
		}
		$tmpCustomers = Customer::all();
		foreach($tmpCustomers as $key=>$val){
			$customers[$key]['name'] = $val->company;
			$customers[$key]['value'] = $val->id;
			$customers[$key]['selected'] = $remind->customers->contains($val->id) ? true : false;
		}

		return view('admin.remind.edit', compact('users', 'repeat', 'customers', 'remind'));
	}

	/**
	 * 编辑保存
	 * @param  Request $request [description]
	 * @param  [type]  $id      [description]
	 * @return [type]           [description]
	 */
	public function update(Request $request, $id){
		$messages = [
			'required' => '数据不能为空',
		];
		$validator = Validator::make($request->all(), [
			'content' => 'bail|required',
			'remind_time' => 'bail|required',
			'user' => 'bail|required',
			// 'repeat' => 'bail|required',
			'customer' => 'bail|required',
		], $messages);

		if($validator->fails()){

			return $this->response($validator->errors()->first());
		}

		
		$remind = Remind::find($id);
		$remind->content = $request->input('content');
		$remind->remind_time = $request->input('remind_time');
		$remind->repeat = $request->input('repeat', 0);

		if($remind->save()){
			$users = explode(',', $request->input('user'));
			$customers = explode(',', $request->input('customer'));
			$remind->users()->sync($users);
			$remind->customers()->sync($customers);

			return $this->response('编辑回访提醒成功', 0);
		}
		return $this->response('编辑回访提醒失败');
	}

	/**
	 * 删除
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function destroy(Request $request, $id){
		if(!$id) $id = $request->input('id');

		if(!$id) return $this->response('删除回访提醒失败', 1);

		if(is_array($id)){
			$remind = Remind::whereIn('id', $id)->get();
		}
		else{
			$remind = Remind::find($id);	
		}
		
		if(Remind::destroy($id)){
			if(is_array($id)){
				foreach($remind as $val){
					$val->users()->detach();
					$val->customers()->detach();
				}
			}
			else{
				$remind->users()->detach();
				$remind->customers()->detach();
			}
			
			return $this->response('删除回访提醒成功', 0);
		}

		return $this->response('删除回访提醒失败', 1);
	}

	/**
	 * 前端提醒
	 * @return [type] [description]
	 */
	public function remind(){
		$remind = [];
		$remind_data = auth('admin')->user()->unreadNotifications;

		if(!$remind_data->count()) return $this->response('', 1, $remind);

		foreach($remind_data as $key=>$val){
			$data = $val->data;
			$info = Remind::find($data['remind_id']);
			$time = strtotime($info->remind_time) - time(); 
			//提醒时间差10s以内，就提醒
			if($time < 10){
				$strStart = strpos($info->content, '(') + 1;
		        $strLen = strpos($info->content, ')') - $strStart;
		        $order_num = substr($info->content, $strStart, $strLen);
		        if($order_num){
		        	$remind[$key]['url'] = route('admin.orders.index', ['order_num'=>$order_num]);
		        }
				
				$remind[$key]['content'] = $info->content;

				if($info->customers->count()){
					$customer = $info->customers->pluck('company')->toArray();
					$remind[$key]['customers'] = implode(',', $customer);
				}

				//标记为已读,本应放在点击确认按钮时，为了减少请求，放这里
				$info->status = 1;
				$info->save();

				$val->markAsRead();
			}
		}

		if($remind) return $this->response('', 0, $remind);

		return $this->response('', 1, $remind);
		
	}
}