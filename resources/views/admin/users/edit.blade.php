@extends('admin.layout')

@section('title', '编辑用户')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="layui-form layui-form-pane valideform">
        	{{ csrf_field() }}
            @method('PUT')

            @if($userGrade != 2)
            <div class="layui-form-item">
                <label for="role" class="layui-form-label">
                    所属角色
                </label>
                <div class="layui-inline">
                    <select name="role" lay-verify="">
                        <option value="">请选择角色</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" @if($user->hasRole($role->id)) selected @endif>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="role" class="layui-form-label">
                    用户等级
                </label>
                <div class="layui-inline">
                    <select name="grade_id" lay-verify="">
                        <option value="0">请选择等级</option>
                        @foreach($grade as $g)
                        <option value="{{ $g->id }}" @if($user->grade_id == $g->id) selected @endif>{{ $g->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    用户名
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="name" autocomplete="off" class="layui-input" value="{{ $user->name }}" @if($userGrade == 2) readonly @else datatype="*4-20" @endif>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="password" class="layui-form-label">
                    密码
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="password" name="password" autocomplete="off" class="layui-input" datatype="*6-30" ignore="ignore">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="password2" class="layui-form-label">
                    确认密码
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="password2" name="password2" autocomplete="off" class="layui-input" datatype="*6-30" recheck="password" ignore="ignore">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="nickname" class="layui-form-label">
                    姓名
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="nickname" name="nickname" autocomplete="off" class="layui-input" value="{{ $user->nickname }}">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="email" class="layui-form-label">
                    邮箱
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="email" name="email" autocomplete="off" class="layui-input" value="{{ $user->email }}" datatype="e" ignore="ignore">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="phone" class="layui-form-label">
                    手机号
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="phone" name="phone" autocomplete="off" class="layui-input" value="{{ $user->phone }}" datatype="m" ignore="ignore">
                </div>
            </div>
            @if(auth('admin')->user()->hasRole(1))
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">
                    拥有权限
                </label>
                <table  class="layui-table layui-input-block">
                    <tbody>
                        @foreach($allPermissions as $val)
                        <tr>
                            <td>
                                <input type="checkbox" name="like1[write]" lay-skin="primary" lay-filter="father" title="{{ $val->name }}" value="{{ $val->id }}">
                            </td>
                            <td>
                                <div class="layui-input-block">
                                    <input type="checkbox" name="permission_id[]" value="{{ $val->id }}" class="layui-hide per{{ $val->id }}"lay-ignore @if($user->hasPermissionTo($val->id)) checked @endif>

                                    @foreach($val->permissions as $v)
                                    @if($v->permissions->count())
                                    <input type="checkbox" name="permission_id[]" value="{{ $v->id }}" class="layui-hide  per{{ $v->id }}"lay-ignore @if($user->hasPermissionTo($v->id)) checked @endif>

                                    @foreach($v->permissions as $p)
                                    <input name="permission_id[]" lay-skin="primary" type="checkbox" title="{{ $p->name }}" value="{{ $p->id }}" pid="{{ $v->id }}" ppid="{{ $val->id }}" lay-filter="son" class="son" @if($user->hasPermissionTo($p->id)) checked @endif>
                                    @endforeach
                                    @else
                                    <input name="permission_id[]" lay-skin="primary" type="checkbox" title="{{ $v->name }}" value="{{ $v->id }}" pid="{{ $val->id }}" lay-filter="son" class="son" @if($user->hasPermissionTo($v->id)) checked @endif>
                                    @endif


                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
            <div class="layui-form-item">
                <button class="layui-btn" type="submit">提交</button>
          </div>
        </form>
    </div>
</div>
@stop

@section('javascript')
<script>
layui.use(['form','layer'], function(){
 	var form = layui.form;
	form.on('checkbox(father)', function(data){

	    if(data.elem.checked){
	        $(data.elem).parent().siblings('td').find('input').prop("checked", true);
	        form.render(); 
	    }
	    else{
	       $(data.elem).parent().siblings('td').find('input').prop("checked", false);
	        form.render();  
	    }
	});
	form.on('checkbox(son)', function(data){
		var len = $(data.elem).parents("td").find("input.son").length;
		var clen = $(data.elem).parents("td").find("input.son:checked").length;
        var id = data.value, pid = $(data.elem).attr("pid"), ppid = $(data.elem).attr("ppid");
		if(clen == len){
			$(data.elem).parents('td').siblings('td').find('input').prop("checked", true);
			form.render();
		}
		else{
			$(data.elem).parents('td').siblings('td').find('input').prop("checked", false);
			form.render();
		}

        if(clen > 0){
            if(ppid != undefined){
                $(".per"+ppid).prop("checked", true);
            }
            $(".per"+pid).prop("checked", true);
        }
        else{
            if(ppid != undefined){
                $(".per"+ppid).prop("checked", false);
            }
            $(".per"+pid).prop("checked", false);
        }
        
	});
});
</script>
@stop