@extends('admin.layout')

@section('title', '角色管理')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" class="layui-form layui-form-pane valideform">
        	{{ csrf_field() }}
            @method('PUT')
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    角色名
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="name" autocomplete="off" class="layui-input" value="{{ $role->name }}" datatype="*">
                </div>
            </div>
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
                                    <input type="checkbox" name="permission_id[]" value="{{ $val->id }}" class="layui-hide per{{ $val->id }}"lay-ignore @if($role->hasPermissionTo($val->id)) checked @endif>

                                    @foreach($val->permissions as $v)
                                    @if($v->permissions->count())
                                    <input type="checkbox" name="permission_id[]" value="{{ $v->id }}" class="layui-hide  per{{ $v->id }}"lay-ignore @if($role->hasPermissionTo($v->id)) checked @endif>

                                    @foreach($v->permissions as $p)
                                    <input name="permission_id[]" lay-skin="primary" type="checkbox" title="{{ $p->name }}" value="{{ $p->id }}" pid="{{ $v->id }}" ppid="{{ $val->id }}" lay-filter="son" class="son" @if($role->hasPermissionTo($p->id)) checked @endif>
                                    @endforeach
                                    @else
                                    <input name="permission_id[]" lay-skin="primary" type="checkbox" title="{{ $v->name }}" value="{{ $v->id }}" pid="{{ $val->id }}" lay-filter="son" class="son" @if($role->hasPermissionTo($v->id)) checked @endif>
                                    @endif


                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="layui-form-item layui-form-text">
                <label for="desc" class="layui-form-label">
                    描述
                </label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="desc" name="desc" class="layui-textarea">{{ $role->desc }}</textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    启用
                </label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="status" lay-skin="switch" lay-text="开启|关闭" value="1" @if($role->status == 1) checked @endif>
                </div>
            </div>
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