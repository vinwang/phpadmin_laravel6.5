@extends('admin.layout')

@section('title', '系统设置')

@section('content')
<div class="x-nav">
  <span class="layui-breadcrumb">
    <a href="">首页</a>
    <a href="">客户管理</a>
    <a>
      <cite>客户列表</cite></a>
  </span>
  <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
    <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row">
       <div class="layui-col-md12">
                    <div class="layui-card">
                     <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5" method="get" action="{{ route('admin.customer.index') }}">
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="name" placeholder="请输入公司名称" autocomplete="off" class="layui-input" value="{{ $name }}">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="user_id">
                                <option value="">请选择归属人</option>
                                @foreach($users as $val)
                                  <option value="{{ $val->id }}" @if($val->id == $user_id) selected @endif>{{ $val->nickname ?: $val->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                  </div>
                        <div class="layui-card-header">
                            <button class="layui-btn layui-btn-danger" onclick="delAll('{{ route('admin.customer.destroy', 0) }}', '{{ csrf_token() }}')"><i class="layui-icon"></i>批量删除</button>
                            <button class="layui-btn" onclick="xadmin.open('添加用户','{{ route('admin.customer.create') }}',800,700)"><i class="layui-icon"></i>添加</button>
                        </div>
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                              <thead>
                                <tr>
                                  <th><input type="checkbox" name=""  lay-skin="primary" lay-filter="checkall"></th>
                                  <th>序号</th>
                                  <th>公司名称</th>
                                  <th>联系人</th>
                                  <th>联系电话</th>
                                  <th>归属人</th>
                                  <th>添加时间</th>
                                  <th>客户来源</th>
                                  <th>操作</th>
                              </thead>
                              <tbody>
                              @foreach($data as $arr)
                                <tr>
                                  <td><input type="checkbox" name="id[]" lay-skin="primary" value="{{ $arr->id }}" class="checkbox"></td>
                                  <td>{{ $loop->iteration }}</td>
                                  <td><a onclick="xadmin.open('客户详情','{{ route('admin.customer.show',$arr->id) }}',1100,600)">{{ $arr->company }}</a></td>
                                  <td>{{ $arr->name }}</td>
                                  <td>{{ $arr->phone }}</td>
                                  <td>{{ $arr->user->nickname ?:  $arr->user->name}}</td>
                                  <td>{{ $arr->created_at }}</td>
                                  <td>{{ $arr->source->name }}</td>
                                  <td class="td-manage">
                                    <div class="layui-btn-container">
                                    <button class="layui-btn layui-btn layui-btn-xs" onclick="xadmin.open('编辑客户','{{ route('admin.customer.edit',$arr->id) }}',800,700)" ><i class="layui-icon">&#xe642;</i>编辑</button>

                                    <a class="layui-btn-danger layui-btn layui-btn-xs" title="删除" onclick="info_del('{{ route('admin.customer.destroy', $arr->id) }}','{{ csrf_token() }}')"><i class="layui-icon">&#xe640;</i>删除</a>
                                    <a class="layui-btn layui-btn-normal" title="退回" onclick="info_return('{{ route('admin.customer.return', ['id'=>$arr->id]) }}','{{ csrf_token() }}')"><i class="layui-icon">&#xe65c;</i>退回</a>
                                  </div>
                                  </td>
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
                        </div>
                        <div class="layui-card-body ">
                            <div class="page">
                                <div>
                                 {{ $data->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
</div>
@stop

@section('javascript')
<script type="text/javascript">
  /*领取*/
function info_return(uri, token){
  layer.confirm('确认要退回吗？',function(index){
    $.ajax({
      type: "GET",
      url: uri,
      headers: {'X-CSRF-TOKEN': token},
      success: function(result){
        if(result.code > 0){
          layer.msg(result.msg, {icon: 5});
          return false;
        }
        else{
          location.reload();
        }
      }
    });
  });
}
</script>
@stop