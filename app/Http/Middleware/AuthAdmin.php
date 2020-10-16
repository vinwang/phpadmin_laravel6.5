<?php

namespace App\Http\Middleware;

use Closure;
use App\Admin;
use App\Models\Permissions;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            //登录后不需要权限判断
            $except = ['admin.logout', 'admin.doremind', 'admin.contract.getorder', 'admin.orders.getcity', 'admin.abutment.getgood', 'admin.abutment.getnode'];
            if(in_array($request->route()->getName(), $except)){

                return $next($request);
            }

            $route = Permissions::where('uri', $request->route()->getName())->value('id');
            // return $next($request);
            // 
            if(Auth::guard($guard)->user()->hasRole(1) || ($route && Auth::guard($guard)->user()->hasPermissionTo($route))){

                return $next($request);
            }
            elseif($request->ajax()){

                return response()->json(['msg'=>'无权进行此操作', 'code'=>1]);
            }
            else{

                throw UnauthorizedException::forPermissions([]);
            }
        }

        $urlLogin = route('admin.login');
        return redirect($urlLogin);
    }
}
