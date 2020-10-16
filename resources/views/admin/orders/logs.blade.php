@extends('admin.layout')

@section('title', '操作日志')

@section('css')
<style type="text/css">
  .icon-color{color:red;}
  .font-color{color: #c2c2c2;}

</style>
@stop

@section('content')
<div class="x-nav">
  <span class="layui-breadcrumb">
    <a href="">首页</a>
    <a href="">系统配置</a>
    <a>
      <cite>操作日志</cite></a>
  </span>
  <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
    <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5" method="get" action="{{ route('admin.orders.logs') }}">
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="comment" placeholder="请输入备注" autocomplete="off" class="layui-input" value="{{ $comment }}">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="user_id">
                                <option value="">请选择操作者</option>
                                @foreach($users as $val)
                                  <option value="{{ $val->id }}" @if($val->id == $user_id) selected @endif>{{ $val->nickname ?: $val->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="starttime" id="starttime" placeholder="请选择起始时间" autocomplete="off" class="layui-input" value="{{ $starttime }}">
                        </div>
                        --
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="endtime" id="endtime" placeholder="请选择终止时间" autocomplete="off" class="layui-input" value="{{ $endtime }}">
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
                          <!-- <th>编号</th> -->
                          <th>备注</th>
                          <th>操作者</th>
                          <th>时间</th>
                      </thead>
                      <tbody>
                        @foreach($lists as $k=>$list)
                        <tr @if($list->read) class="font-color" @endif data-id="{{ $list->id }}">
                          <!-- <td>{{ $loop->iteration }}</td> -->
                          <td>
                            <i class="layui-icon layui-icon-notice @if(!$list->read) icon-color @endif"></i>
                            @if($adminRole == 4) 
                            {{ preg_replace('/\d+\,+\d+|\d+(?=元)/i', '***', $list->comment) }}
                            @else
                            {{ $list->comment }}
                            @endif
                          </td>
                          <td>{{ $list->user->nickname ?: $list->user->name }}</td>
                          <td>{{ $list->updated_at }}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                </div>
                <div class="layui-card-body ">
                    <div class="page">
                        <div>
                          {{ $lists->links() }}
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
layui.use(['laydate', 'layer', 'jquery'], function(){
  var laydate = layui.laydate;
  var layer = layui.layer;
  var $ = layui.jquery;
  
  //执行一个laydate实例
  laydate.render({
      elem: '#starttime',
      trigger: 'click',
      type: 'datetime'
  });

  //执行一个laydate实例
  laydate.render({
      elem: '#endtime',
      trigger: 'click',
      type: 'datetime'
  });

  $("tr:gt(0)").click(function(){
    var obj = $(this);
    var id = obj.attr("data-id");
    var content = obj.find("td:eq(0)").text();
    $.ajax({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: "POST",
      url: "{{ route('admin.orders.logs') }}",
      dataType: "JSON",
      data: {read:1, id:id, },
      success: function(result){
        if(result.code == 0){
          obj.addClass("font-color");
          obj.find("td:eq(0) i").removeClass("icon-color");
          layer.open({
            content: content
          });
        }
      }
    });
  });

});
</script>
@stop