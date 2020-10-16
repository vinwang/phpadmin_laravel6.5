@extends('admin.layout')

@section('title', '系统设置')

@section('content')
<div class="x-nav">
  <span class="layui-breadcrumb">
    <a href="">首页</a>
    <a href="">客户管理</a>
    <a>
      <cite>客户公海</cite></a>
  </span>
  <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
    <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row">
       <div class="layui-col-md12">
                    <div class="layui-card">
                     <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5" method="get" action="{{ route('admin.sea.index') }}">
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="name" placeholder="请输入公司名称" autocomplete="off" class="layui-input" value="{{ $name }}">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                  </div>
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                              <thead>
                                <tr> 
                                  <th>ID</th>
                                  <th>公司名称</th>
                                  <th>联系人</th>
                                  <th>联系电话</th>
                                  <th>状态</th>
                                  <th>创建人</th>  
                                  <th>编辑时间</th>
                                  <th>客户来源</th>
                                  <th>操作</th>
                              </thead>
                              <tbody>
                              @foreach($data as $arr)
                                <tr> 
                                  <td>{{ $arr->id }}</td>
                                  <td>{{ $arr->company }}</td>
                                  <td>{{ $arr->name }}</td>
                                  <td>{{ $arr->phone }}</td> 
                                  <td>@if($arr->status ==0) 未分配 @else 已分配 @endif</td> 
                                  <td>{{$user[$arr->uid]}}</td>  
                                  <td>{{ $arr->updated_at }}</td> 
                                  <td>{{$source[$arr->source_id]}}</td>
                                  <td class="td-manage">
                                    <div class="layui-btn-container">
                                    @if($username->hasPermissionTo(86))
                                      <button class="layui-btn layui-btn layui-btn-xs" onclick="xadmin.open('分配','{{ route('admin.sea.edit',$arr->id) }}',
                                      500,300)" ><i class="layui-icon">&#xe609;</i>分配</button>
                                    @endif  
                                      <a class="layui-btn layui-btn-normal" title="领取" onclick="info_receive('{{ route('admin.sea.store', ['id'=>$arr->id]) }}','{{ csrf_token() }}')">
                                        <i class="layui-icon">&#xe67b;</i>领取
                                      </a>
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
<script type="text/javascript">
  /*领取*/
function info_receive(uri, token){
  layer.confirm('确认要领取吗？',function(index){
    $.ajax({
      type: "POST",
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