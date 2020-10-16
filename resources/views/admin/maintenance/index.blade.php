@extends('admin.layout')

@section('title', '维保订单列表')

@section('content')

<div class="x-nav">
  <span class="layui-breadcrumb">
    <a href="">首页</a>
    <a href="">维保订单</a>
    <a>
      <cite>维保订单列表</cite></a>
  </span>
  <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
    <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5" method="get" action="{{ route('admin.maintenance.index') }}">
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="customer_name" placeholder="请输入客户名称" autocomplete="off" class="layui-input" value="{{ request('customer_name') }}">
                        </div>

                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" class="layui-input" id="date" autocomplete="off" placeholder="选择月份" name="date" value="{{ request('date') }}">
                        </div>

                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="contract_starttime" id="contract_starttime" placeholder="请选择合同起始时间" autocomplete="off" class="layui-input" value="{{ request('contract_starttime') }}">
                        </div>
                        --
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="contract_endtime" id="contract_endtime" placeholder="请选择合同结束时间" autocomplete="off" class="layui-input" value="{{ request('contract_endtime') }}">
                        </div>

                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-header">
                  <button class="layui-btn layui-btn-danger" onclick="delAll('{{ route('admin.maintenance.destroy', 0) }}', '{{ csrf_token() }}')"><i class="layui-icon"></i>批量删除</button>
                  <button class="layui-btn" onclick="xadmin.open('添加维保订单','{{ route('admin.maintenance.create') }}',1100,600)"><i class="layui-icon"></i>添加</button>
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                          <tr>
                            <th style="min-width:20px;"><input type="checkbox" name=""  lay-skin="primary" lay-filter="checkall"></th>
                            <th>客户名称</th>
                            <th>对应订单编号</th>
                            <th>业务种类/节点名称/系统厂商/sn号</th>
                            <th>合同起始时间</th>
                            <th>合同结束时间</th>
                            <th>创建人</th>
                            <th>操作</th>
                        </thead>
                        <tbody>
                          @foreach($lists as $list)
                            <tr>
                              <td style="min-width:20px;"><input type="checkbox" name="id[]" lay-skin="primary" value="{{ $list->id }}" class="checkbox"></td>
                              <td><a href="javascript:;"  onclick="xadmin.open('详情','{{ route('admin.maintenance.show',$list->id) }}',1000,650);" class="remark" value="{{ $list->remarks }}">{{ $list->customer->company }}</a></td>
                              <td>
                                @foreach(explode(',', $list->order_id) as $val)
                                  {{ $order[$val] }}<br>
                                @endforeach
                              </td>

                              <td>
                                @foreach(unserialize($list->device_msg) as $v)
                                  <a title="{{ $v['address'] }}" style="cursor: pointer;">{{ $v['good_id'] ? $good[$v['good_id']] : '' }}&emsp;{{ $v['node_id'] ? $province[$v['node_id']] : '' }}&emsp;{{ $v['device_number'] ?: '' }}&emsp;{{ $v['sn_number'] ?: '' }}</a><br>
                                @endforeach
                              </td>

                              <td>{{ $list->contract_starttime ? date('Y-m-d',$list->contract_starttime) : '' }}</td>
                              <td>{{ $list->contract_endtime ? date('Y-m-d',$list->contract_endtime) : '' }}</td>
                              <td>{{ $list->user->nickname ?: $list->user->name }}</td>
                              <td class="td-manage">
                                <button class="layui-btn layui-btn-xs" title="编辑" onclick="xadmin.open('编辑维保订单','{{ route('admin.maintenance.edit',$list->id) }}',1100,600);" ><i class="layui-icon">&#xe642;</i>编辑</button>
                                <a class="layui-btn-danger layui-btn layui-btn-xs" title="删除" onclick="info_del('{{ route('admin.maintenance.destroy', $list->id) }}','{{ csrf_token() }}')">
                                  <i class="layui-icon">&#xe640;</i>删除
                                </a>
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
$(function(){
    var tips;
    $('.remark').on({
        mouseenter:function(){
            var that = this;
            if($(this).attr('value')) tips =layer.tips("<span>"+$(this).attr('value')+"</span>",that,{tips:[2,'#009688'],time:0,area: 'auto',maxWidth:500});
        },
        mouseleave:function(){
            layer.close(tips);
        }
    });
})

layui.use(['laydate'], function(){
    var laydate = layui.laydate;

    //执行一个laydate实例
    laydate.render({
        elem: '#contract_starttime',
        trigger: 'click'
    });

    //执行一个laydate实例
    laydate.render({
        elem: '#contract_endtime',
        trigger: 'click'
    });

    //执行一个laydate实例
    laydate.render({
        elem: '#date',
        type: 'month', 
    });
});
</script>
@stop