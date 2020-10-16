<?php
namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Models\Permissions;
use App\Models\Source;
use App\Models\Customer;
use App\Models\Orders;
use App\Models\OrdersCost;
use App\Models\Maintenance;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Admin\BackendController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class IndexController extends BackendController{


	public function index(){
		//超级管理员菜单
		$superMenu = [
			[
				'name' => '销售部',
				'menus' => 
				[
					[
						'name' => '客户标签',
						'uri' => route('admin.tags.index'),
					],
					[
						'name' => '客户来源',
						'uri' => route('admin.source.index'),
					],
					[
						'name' => '客户列表',
						'uri' => route('admin.customer.index'),
					],
					[
						'name' => '业务种类',
						'uri' => route('admin.goods.index'),
					],
					[
						'name' => '订单列表',
						'alias' => '订单列表 - 销售部',
						'uri' => route('admin.orders.index', ['role'=>2]),
					],
					[
						'name' => '合同列表',
						'uri' => route('admin.contract.index'),
					],
				]
			],
			[
				'name' => '文案部',
				'menus' => 
				[
					[
						'name' => '订单列表',
						'alias' => '订单列表 - 文案部',
						'uri' => route('admin.orders.index', ['role'=>3]),
					],
					[
						'name' => '资质列表',
						'uri' => route('admin.contract.qualified'),
					],
				]
			],
			[
				'name' => '技术部',
				'menus' => 
				[
					[
						'name' => '订单列表',
						'alias' => '订单列表 - 技术部',
						'uri' => route('admin.orders.index', ['role'=>4]),
					],
					[
						'name' => '维保订单',
						'uri' => route('admin.maintenance.index'),
					],
					[
						'name' => '对接订单',
						'uri' => route('admin.abutment.index'),
					],
				]
			],
			[
				'name' => '数据分析',
				'menus' => 
				[
					[
						'name' => '客户分析',
						'uri' => route('admin.customer.analysi'),
					],
					[
						'name' => '订单分析',
						'uri' => route('admin.orders.analysi'),
					],
				]
			],
			[
				'name' => '用户管理',
				'menus' => 
				[
					[
						'name' => '用户管理',
						'uri' => route('admin.users.index'),
					],
					[
						'name' => '角色管理',
						'uri' => route('admin.roles.index'),
					],
					[
						'name' => '用户等级',
						'uri' => route('admin.grade.index'),
					],
				]
			],
			[
				'name' => '系统设置',
				'menus' => 
				[
					[
						'name' => '客户公海',
						'uri' => route('admin.sea.index'),
					],
					[
						'name' => '回访提醒',
						'uri' => route('admin.remind.index'),
					],
					[
						'name' => '系统配置',
						'uri' => route('admin.system.settings'),
					],
					[
						'name' => '操作日志',
						'uri' => route('admin.system.logs'),
					],
					[
						'name' => '订单操作日志',
						'uri' => route('admin.orders.logs'),
					],
				]
			],
		];
		$admin = auth('admin')->user();
		$role = auth('admin')->user()->roles->first();

		$menus = (new Permissions)->bubblingPermissions(0, ['status'=>1]);

		$template = 'admin.index';
		
		/*if($role->id == 1){
			$template = 'admin.index1';
		}*/
		return view($template, compact('menus', 'role', 'superMenu', 'admin'));
	}

	/**
	 * 仪表盘
	 * @return [type] [description]
	 */
	public function dashboard(Request $request){
		$admin = auth('admin')->user();
		$roles  = $admin->roles->first()->id;
		if($roles == 4){
			$data = $this->dashboard_js($request->input('date'), $request->input('user_id'));
			return view('admin.dashboard_js',compact('data','admin','roles'));
		}else{
			$source_name = '';
			$source_num = [];
			$where = [];
			$sql = [];
			
			if($roles != 1){
				$where['user_id'] = $admin->id;
				$sql['uid'] = $admin->id;
			}
			$path = base_path();
	        $disk_total = disk_total_space($path);
	        $disk_free = disk_free_space($path);
	        $disk_use = round(($disk_total - $disk_free) * 100 / ($disk_total));

	        $sources = Source::with(['customers'=>function($query) use($admin, $roles){
	        	if($roles != 1){
	        		$query->where('uid', $admin->id);
	        	}
	        }])->get();

	        foreach($sources as $key=>$val){
	        	$source_name .= "'".$val['name']."'".',';
	        	$source_num[$key][] = $val->customers->count(); 
	        	$source_num[$key][] = $val['name'];
	        }
			
			$custom_count = '';
			$order_count = '';
			$order_money = '';
			$time = time();
			//获取当前周几
			$week = date('w', $time);
			$monday = date('Y-m-d', ($time - ((($week == 0 ? 7 : $week) - 1) * 24 * 3600)));
			$sunday = date('Y-m-d', ($time + ((7 - ($week == 0 ? 7 : $week)) * 24 * 3600)));

			$customers = Customer::select(['company', 'created_at'])->whereBetween('created_at', [$monday, $sunday])->where($sql)->get();
			$orders = Orders::select(['plannedamt', 'created_at'])->whereBetween('created_at', [$monday, $sunday])->where($where)->get();
			
			for ($i=1; $i<=7; $i++){
				$date = date('Y-m-d', strtotime( '+' . ($i-$week) .' days', $time));
				$tmpCustomerCount = 0 ;
				$tmpOrderCount = 0 ;
				$tmpOrderAmount = 0 ;
				foreach($customers as $cus){
					if(date('Y-m-d', strtotime($cus->created_at)) == $date){
						$tmpCustomerCount++;	
					}
				}
				foreach($orders as $order){
					if(date('Y-m-d', strtotime($order->created_at)) == $date){
						$tmpOrderCount++;
						$tmpOrderAmount += $order->plannedamt;
					}
				}
				$custom_count .= $tmpCustomerCount . ',';
				$order_count .= $tmpOrderCount . ',';
				$order_money .= $tmpOrderAmount . ',';
			}

			$custom_count = substr($custom_count, 0, -1);
			$order_count = substr($order_count, 0, -1);
			$order_money = substr($order_money, 0, -1);
			
			return view('admin.dashboard',compact('disk_use','source_name','source_num','custom_count','order_count','order_money'));
			
		}
	}

	/**
	 * 技术部订单成本分析
	 * @return [type] [description]
	 */
	private function dashboard_js($date = '', $user_id)
	{
		$start = '';
        $end = '';
		if($date){
            $start = date('Y-m-01 00:00:00',strtotime($date));
            $end = date('Y-m-t 23:59:59',strtotime($date));   
		}
		$where = [];
		$sql = [];
		if($user_id){
			$where['id'] = $user_id;
			$sql['user_id'] = $user_id;
		}else{
			$where['id'] = auth('admin')->user()->id;
			if(Admin::where('grade_id', 1)->whereHas('roles', function($query){$query->where('id', 4);})->value('id') !== auth('admin')->user()->id){
				$sql['user_id'] = auth('admin')->user()->id;
			}
		}
		//硬盘使用量
		$path = base_path();
        $disk_total = disk_total_space($path);
        $disk_free = disk_free_space($path);
        $disk_use = round(($disk_total - $disk_free) * 100 / ($disk_total));

        //最新一月新增订单
        if($date){
        	$dates = date("t",strtotime($date));
        }else{
        	$dates = date('t');
        }
        
        $month = '';
        //一个月的天数
        for($i = 1; $i <= $dates; $i++){
            $month .= $i.',';
        }

        //当月第一天
        if($date){
        	$firstDay = $date.'-01';
        }else{
        	$firstDay = date('Y-m-01');
        }
        
        //当月最后一天
        $lastDay = date('Y-m-d', strtotime("$firstDay +1 month -1 day"));
        //技术部主管\普通员工id
        $js_id = Admin::where($where)->whereHas('roles', function($query){
                    $query->where('id', 4);
                })->first();

        $order_id = [];
        $orderLastCount = [];
        $orderLastAmount = [];
        //流转给技术部主管的订单id
        foreach ($js_id->records as $key => $value) {
        	$order_id[] = $value->order_id;
        }


        //完成订单总量
        $finish_count = Orders::whereIn('id',$order_id)->where('jsverify',4)->where(function($query) use($date, $start, $end){
				            if($date){
				                $query->whereBetween('created_at', [$start, $end]);
				            }
				        })->count();
        //流转给技术部主管的全部订单
        $allOrders = Orders::whereIn('id',$order_id)->select(['id', 'created_at'])->where(function($query) use($date, $start, $end){
            if($date){
                $query->whereBetween('created_at', [$start, $end]);
            }
        })->get()->toArray();
        $ordersId = $allOrders ? Arr::pluck($allOrders, 'id') : [];
        //流转订单总量
        $allOrders_count = count($allOrders);
        $i = 0;
        $monthDay = date('Y-m-d', strtotime("$firstDay +$i days"));
        while ($monthDay <= $lastDay) {
            $monthDay = date('Y-m-d', strtotime("$firstDay +$i days"));
            $tmpCount = 0;
            $tmpAmount = 0;
            foreach($allOrders as $tmpOrder){
                if(date('Y-m-d', strtotime($tmpOrder['created_at'])) == $monthDay){
                    $tmpCount++;
                    $tmpAmount = Orders::whereIn('id',$order_id)->where('jsverify',4)->whereDate('created_at',date('Y-m-d', strtotime($tmpOrder['created_at'])))->count();
                }
            }
            $orderLastCount[] = $tmpCount;
            $orderLastAmount[] = $tmpAmount;
            $i++;
        }
        //最新一月流转订单量
        $order_count = implode(',', $orderLastCount);
        //最新一月完成订单量
        $order_finish_count = implode(',', $orderLastAmount);

        //最新一月新增成本金额
        $cost = OrdersCost::whereIn('order_id', $ordersId)
                    ->selectRaw('sum(equipment_cost) as equipmentCost, sum(trusteeship_cost) as trusteeshipCost, sum(jishu_other_cost) as jishuOtherCost, sum(software_cost) as softwareCost')->first();
        //成本总额
        $cost_allmoney = array_sum([$cost->equipmentCost,$cost->trusteeshipCost,$cost->jishuOtherCost,$cost->softwareCost]);
        //技术部普通员工id
        $js_put_id = Admin::where('grade_id', '<>', 1)->whereHas('roles', function($query){
                    $query->where('id', 4);
                })->get();

        //维保订单
        $maintenance = Maintenance::where($sql)->selectRaw('sum(deposit_money) as depositMoney, sum(cost_money) as costMoney, sum(total) as total')->where(function($query) use($date, $start, $end){
            if($date){
                $query->whereBetween('created_at', [$start, $end]);
            }
        })->first();
        return array('month' => $month,'disk_use' => $disk_use, 'order_count' => $order_count, 'order_finish_count' => $order_finish_count, 'cost' => $cost, 'allOrders_count' => $allOrders_count, 'finish_count' => $finish_count, 'cost_allmoney' => $cost_allmoney, 'js_put_id' => $js_put_id, 'maintenance' => $maintenance);
	}
}