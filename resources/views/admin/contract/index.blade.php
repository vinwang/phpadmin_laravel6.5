@extends('admin.layout')

@section('title', '合同列表')

@section('content')

<div class="x-nav">
  <span class="layui-breadcrumb">
    <a href="">首页</a>
    <a href="">合同管理</a>
    <a>
      <cite>合同列表</cite></a>
  </span>
  <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
    <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5" method="get" action="{{ route('admin.contract.index') }}">
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="title" placeholder="请输入合同标题" autocomplete="off" class="layui-input" value="{{ $title }}">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="customer_id" placeholder="请输入客户名称" autocomplete="off" class="layui-input" value="{{ $custom_name }}">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="starttime" id="starttime" placeholder="请选择开始时间" autocomplete="off" class="layui-input" value="{{ $starttime }}">
                        </div>
                        --
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="endtime" id="endtime" placeholder="请选择截止时间" autocomplete="off" class="layui-input" value="{{ $endtime }}">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="number" placeholder="请输入订单编号" autocomplete="off" class="layui-input" value="{{ $number }}">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="month" id="month" placeholder="请选择合同月份" autocomplete="off" class="layui-input" value="{{ request('month') }}">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-header">
                  <button class="layui-btn layui-btn-danger" onclick="delAll('{{ route('admin.contract.destroy', 0) }}', '{{ csrf_token() }}')"><i class="layui-icon"></i>批量删除</button>
                  <button class="layui-btn" onclick="xadmin.open('添加合同','{{ route('admin.contract.create') }}',1100,660)"><i class="layui-icon"></i>添加</button>
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                          <tr>
                            <th style="min-width:20px;"><input type="checkbox" name=""  lay-skin="primary" lay-filter="checkall"></th>
                            <th>合同标题</th>
                            <th>客户名称</th>
                            <th>开始时间</th>
                            <th>截止时间</th>
                            <th>合同编号</th>
                            <th>合同备注</th>
                            <th>操作</th>
                        </thead>
                        <tbody>
                          @foreach($lists as $list)
                            <tr>
                              <td style="min-width:20px;"><input type="checkbox" name="id[]" lay-skin="primary" value="{{ $list->id }}" class="checkbox"></td>
                              <td><a href="javascript:;"  onclick="xadmin.open('详情','{{ route('admin.contract.show',$list->id) }}',800,650);">{{ $list->title }}</a></td>
                              <td>{{ $list->customer->company }}</td>
                              <td>{{ $list->starttime ? date('Y-m-d',$list->starttime) : '' }}</td>
                              <td>{{ $list->endtime ? date('Y-m-d',$list->endtime) : '' }}</td>
                              <td>{{ $list->number }}</td>
                              <td>{{ $list->remark }}</td>
                              <td class="td-manage">
                                <div class="layui-btn-container">
                                <button class="layui-btn layui-btn-xs" title="编辑" onclick="xadmin.open('编辑合同','{{ route('admin.contract.edit',$list->id) }}',1100,660);" ><i class="layui-icon">&#xe642;</i>编辑</button>
                                <a class="layui-btn-danger layui-btn layui-btn-xs" title="删除" onclick="info_del('{{ route('admin.contract.destroy', $list->id) }}','{{ csrf_token() }}')">
                                  <i class="layui-icon">&#xe640;</i>删除
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
<script>
layui.use('laydate', function() {
    var laydate = layui.laydate;

    //执行一个laydate实例
    laydate.render({
        elem: '#starttime' //指定元素
    });

    //执行一个laydate实例
    laydate.render({
        elem: '#endtime' //指定元素
    });
    laydate.render({
        elem: '#month', //指定元素
        type: 'month', 
    });
});
</script>
@stop