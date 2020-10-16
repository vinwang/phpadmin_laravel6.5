<?php
namespace App\Http\Controllers\Admin;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use App\Admin;
use Spatie\Permission\Models\Role;
use App\Models\Permissions;
use App\Models\Sysconf;
use App\Models\SystemLog;
use App\Http\Controllers\Admin\BackendController;

class SystemController extends BackendController{

	public function logs(Request $request){

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
        
		$lists = SystemLog::where($where)
			->whereHas('user', function($query){
				$query->where('status', 1);
			})
			->where(function($query){
                $user = auth('admin')->user();
                if(!$user->hasRole(1) && !$user->hasPermissionTo(46)){

                    $query->where('user_id', $user->id);
                }
            })
			->orderBy('id','desc')
			->paginate(10)->appends($request->all());
			
		$users = Admin::where('status', 1)->get();
        
		return view('admin.system.logs',compact('lists','users','comment','user_id','starttime','endtime'));
	}

	/**
	 * 系统设置
	 * @return [type] [description]
	 */
	public function settings(Request $request){
		if($request->isMethod('post') && $request->all()){
			$data = $request->all();
			unset($data['_token']);
			$success = false;
			foreach($data as $key=>$val){
				if(!is_numeric($val)){
					return $this->response('数据格式不正确，请填写数字', 1);
					break;
				}

				$success = Sysconf::updateOrCreate(
					['name'=>$key],
					['value'=>$val]
				);
				if(!$success){
					return $this->response('保存系统设置失败', 1);
					break;
		        }
			}
			if(!Cache::has('sysconfig') || $success){
				$tmpConfig = Sysconf::all()->toArray();
				$config = Arr::pluck($tmpConfig, 'value', 'name');

				Cache::put('sysconfig', $config);
			}

	        
	        return $this->response('保存系统设置成功', 0);
		}
		
		$config = Cache::rememberForever('sysconfig', function(){

			$tmpConfig = Sysconf::all()->toArray();
			return Arr::pluck($tmpConfig, 'value', 'name');
		});

		return view('admin.system.settings', compact('config'));
	}
}