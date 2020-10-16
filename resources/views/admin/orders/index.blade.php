@extends('admin.layout')

@section('title', '订单列表')

@section('content')

<div class="x-nav">
  <span class="layui-breadcrumb">
    <a href="">首页</a>
    <a href="">订单管理</a>
    <a>
      <cite>订单列表</cite></a>
  </span>
  <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
    <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5" method="get" action="{{ route('admin.orders.index', ['role'=>$role]) }}">
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="order_num" placeholder="请输入订单编号" autocomplete="off" class="layui-input" value="{{ $order_num }}">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="customer_id" placeholder="请输入客户名称" autocomplete="off" class="layui-input" value="{{ $customer_id }}">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="goods">
                                <option value="">请选择业务种类</option>
                                @foreach($goods as $key=>$val)
                                  <option value="{{ $val->id }}" @if($val->id == request('goods')) selected @endif>{{ $val->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($roles == 1 || $roles == 3)
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="order_type">
                                <option value="">请选择订单类型</option>
                                @foreach($orderType as $key=>$val)
                                  <option value="{{ $key }}" @if($key == $order_type) selected @endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="increment" placeholder="请输入增值服务" autocomplete="off" class="layui-input" value="{{ $increment }}">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="licence" placeholder="请输入许可证号" autocomplete="off" class="layui-input" value="{{ $licence }}">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="licence_start" id="licence_start" placeholder="请选择许可证起始时间" autocomplete="off" class="layui-input" value="{{ $licence_start }}">
                        </div>
                        --
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="licence_end" id="licence_end" placeholder="请选择许可证截止时间" autocomplete="off" class="layui-input" value="{{ $licence_end }}">
                        </div>
                        @endif
                        @if($roles != 4)
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="process">
                                <option value="">请选择业务状态</option>
                                @foreach($process as $key=>$val)
                                  <option value="{{ $key }}" @if($key == $pprocess) selected @endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="wenan_status">
                                <option value="">请选择文案部订单状态</option>
                                <option value="0" @if(strlen($wenan_status) && ($wenan_status == 0)) selected @endif>待审核</option>
                                <option value="1" @if(strlen($wenan_status) && ($wenan_status == 1)) selected @endif>审核通过</option>
                                <option value="2" @if(strlen($wenan_status) && ($wenan_status == 2)) selected @endif>审核未通过</option>
                                <option value="3" @if(strlen($wenan_status) && ($wenan_status == 3)) selected @endif>退回</option>
                                <option value="4" @if(strlen($wenan_status) && ($wenan_status == 4)) selected @endif>已完成</option>
                            </select>
                        </div>
                        @endif
                        @if($roles != 3)
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="jishu_status">
                                <option value="">请选择技术部订单状态</option>
                                <option value="0" @if(strlen($jishu_status) && ($jishu_status == 0)) selected @endif>待审核</option>
                                <option value="1" @if(strlen($jishu_status) && ($jishu_status == 1)) selected @endif>审核通过</option>
                                <option value="2" @if(strlen($jishu_status) && ($jishu_status == 2)) selected @endif>审核未通过</option>
                                <option value="3" @if(strlen($jishu_status) && ($jishu_status == 3)) selected @endif>退回</option>
                                <option value="4" @if(strlen($jishu_status) && ($jishu_status == 4)) selected @endif>已完成</option>
                            </select>
                        </div>
                        @endif
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="user_id">
                                <option value="">请选择归属人</option>
                                @foreach($users as $val)
                                  <option value="{{ $val->id }}" @if($val->id == $user_id) selected @endif>{{ $val->nickname ?: $val->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" class="layui-input" id="analysidate" autocomplete="off" placeholder="选择月份" name="analysidate" value="{{ request('analysidate') }}">
                        </div>
                        @if(in_array($roles,[1,2,3,4,5]))
                        <div class="layui-input-inline layui-show-xs-block">
                            <select name="search">
                                <option value="">订单筛选</option>
                                <option value="0" @if(request('search') == 0 && request('search') !== null) selected @endif>未流转</option>
                                <option value="1" @if(request('search') == 1) selected @endif>已流转</option>
                                <option value="2" @if(request('search') == 2) selected @endif>发货</option>
                                <option value="3" @if(request('search') == 3) selected @endif>未发货</option>
                                <option value="4" @if(request('search') == 4) selected @endif>收到合同</option>
                                <option value="5" @if(request('search') == 5) selected @endif>未收到合同</option>
                                <option value="6" @if(request('search') == 6) selected @endif>有退款</option>
                                <option value="7" @if(request('search') == 7) selected @endif>无退款</option>
                                <option value="8" @if(request('search') == 8) selected @endif>已开票或已收款</option>
                                <option value="9" @if(request('search') == 9) selected @endif>未开票未收款</option>
                                <option value="10" @if(request('search') == 10) selected @endif>技术部成本未记录</option>
                            </select>
                        </div>
                        @endif
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
                @if($roles == 1 || $roles == 2)
                <div class="layui-card-header">
                  <button class="layui-btn layui-btn-danger" onclick="delAll('{{ route('admin.orders.destroy', 0) }}', '{{ csrf_token() }}')"><i class="layui-icon"></i>批量删除</button>
                  <button class="layui-btn" onclick="xadmin.open('添加订单','{{ route('admin.orders.create') }}',1100,600)"><i class="layui-icon"></i>添加</button>
                </div>
                @endif
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                          <tr>
                          @if($roles == 1 || $roles == 2)
                            <th style="min-width:20px;"><input type="checkbox" name=""  lay-skin="primary" lay-filter="checkall"></th>
                            @endif
                            <th>订单编号</th> 
                            <th>客户名称</th>
                            @if($roles == 1 || $roles == 2 || $roles == 5)
                            <th>合同金额</th>
                            @endif
                            <th>
                              @if($roles == 3)
                                文案部业务状态
                              @else
                                技术部业务状态
                              @endif
                            </th>
                            @if($roles != 3 && $roles != 4)
                            <th>文案部业务状态</th>
                            @endif
                            <th>审核状态</th>
                            <th>归属人</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </thead>
                        <tbody>
                          @foreach($lists as $list)
                            <tr
                            @if($roles == 3) @if(!in_array(18, explode(',',$list->process)) && time() - strtotime($list->updated_at) > 15*24*3600)
                            class="layui-bg-orange"
                            @endif @endif
                            >
                            @if($roles == 1 || $roles == 2)
                              <td style="min-width:20px;"><input type="checkbox" name="id[]" lay-skin="primary" value="{{ $list->id }}" class="checkbox"></td>
                              @endif
                              <td>
                                @php
                                $sk = 0;
                                $stages = unserialize($list->stages);
                                foreach($stages as $val){
                                    if($val['billing_time'] && empty($val['collection_time'])){
                                        $billing_time = time() - strtotime($val['billing_time']);
                                        if($billing_time > $sysconfig['sk_remind_time'] * 24 * 3600){
                                            $sk = 1;
                                            break;
                                        }
                                    }
                                }
                                @endphp
                                @if($sk)
                                <i class="layui-icon layui-icon-rmb" style="color:red;font-size: 1.3rem;" title="需要收款"></i>
                                @endif
                                @if($list->remarks)
                                    <i class="layui-icon layui-icon-flag" style="color:red;font-size: 1.3rem;" title="订单有备注"></i>
                                @endif
                                @if($list->shipped && $list->receivables->count())
                                    <i class="layui-icon layui-icon-cart" style="color:red;font-size: 1.3rem;" title="可发货"></i>
                                @endif

                                <a href="javascript:;" onclick="xadmin.open('详情审核','{{ route('admin.orders.show',$list->id) }}',1100,600);" value="{{ $list->remarks }}" class="remarks">{{ $list->order_num }}</a>
                              </td>

                              <td>{{ $list->customer->company }}</td>

                              @if($roles == 1 || $roles == 2 || $roles == 5)
                              <td>￥{{ number_format($list->plannedamt) }}</td>
                              @endif
                              <td>
                                @if($roles == 3)
                                  <a href="javascript:;" value="{{ $list->notes }}" class="process">
                                      <span style="color: @if($list->process == 18) green @endif">{{ $process[$list->process] }}</span>
                                      @if($list->orderActionRecord->count())
                                        ( {{ $list->orderActionRecord->first()->user->nickname ?: $list->orderActionRecord->first()->user->name }} )
                                      @endif
                                  </a>
                                @else
                                <button class="layui-btn layui-btn-normal" title="查看" onclick="xadmin.open('查看业务状态','{{ route('admin.orders.process',$list->id) }}',1100,600);" >查看</button>
                                @endif
                              </td>
                              @if($roles != 3 && $roles != 4)
                              <td>
                                <a href="javascript:;" value="{{ $list->notes }}" class="process">
                                    <span style="color: @if($list->process == 18) green @endif">{{ $process[$list->process] }}</span>

                                    @if($list->orderActionRecord->count())
                                      ( {{ $list->orderActionRecord->first()->user->nickname ?: $list->orderActionRecord->first()->user->name }} )
                                    @endif
                                </a>
                              </td>
                              @endif
                              <td>
                                <a href="javascript:;" value="{{ $list->wa_content }}" class="waverify">
                                  <span style="color: @if(in_array($list->waverify,[1,4])) green @elseif(in_array($list->waverify,[2,3])) red @else blue @endif">文案部{{ $verify[$list->waverify] }}</span><br>
                                  
                                </a>
                                @if($list->jsverify)
                                <a href="javascript:;" value="{{ $list->js_content }}" class="jsverify">
                                  <span style="color: @if(in_array($list->jsverify,[1,4])) green @elseif(in_array($list->jsverify,[2,3])) red @else blue @endif">技术部{{ $verify[$list->jsverify] }}</span>
                                </a>
                                @endif
                              </td>
                              <td>{{ $list->user->nickname ?: $list->user->name  }}</td>
                              <td>{{ $list->created_at }}</td>
                              <td class="td-manage">
                                <div class="layui-btn-container">
                                @if($list->status == 2)
                                    <button class="layui-btn layui-btn-normal" title="已完成"><i class="layui-icon">&#xe6c6;</i>已完成</button>
                                @elseif($list->status == 1)
                                    @if($roles == 1 || $roles == 2)
                                    <button class="layui-btn layui-btn-xs" title="开启" onclick="xadmin.open('业务开启','{{ route('admin.orders.stop', $list->id) }}',800,350);" ><i class="layui-icon">&#xe605;</i>开启</button>
                                    @endif
                                @elseif(in_array($list->status, [0,3,4,5]))
                                    @if($show && !in_array($list->waverify,[1,3,4]) && $roles == 3)
                                      <button class="layui-btn layui-btn-normal" title="审核" onclick="xadmin.open('审核','{{ route('admin.orders.show',$list->id) }}',1100,600);" ><i class="layui-icon">&#xe642;</i>审核</button>
                                    @elseif($show && !in_array($list->jsverify,[1,2,3,4]) && $roles == 4)
                                      <button class="layui-btn layui-btn-normal" title="审核" onclick="xadmin.open('审核','{{ route('admin.orders.show',$list->id) }}',1100,600);" ><i class="layui-icon">&#xe642;</i>审核</button>
                                    @elseif($list->waverify != 4 && $admin->hasPermissionTo(64) && $roles == 3)
                                      <button class="layui-btn layui-btn-xs" title="编辑" onclick="xadmin.open('编辑订单','{{ route('admin.orders.edit',$list->id) }}',1100,600);" ><i class="layui-icon">&#xe642;</i>编辑</button>
                                    @elseif($list->jsverify != 4 && $admin->hasPermissionTo(64) && $roles == 4)
                                      <button class="layui-btn layui-btn-xs" title="编辑" onclick="xadmin.open('编辑订单','{{ route('admin.orders.edit',$list->id) }}',1100,600);" ><i class="layui-icon">&#xe642;</i>编辑</button>
                                    @elseif($admin->hasPermissionTo(64) && !in_array($roles,[3,4,5]))
                                      <button class="layui-btn layui-btn-xs" title="编辑" onclick="xadmin.open('编辑订单','{{ route('admin.orders.edit',$list->id) }}',1100,600);" ><i class="layui-icon">&#xe642;</i>编辑</button>
                                    @endif
                                    @if($roles == 1 || $roles == 2 || $roles == 5)
                                        @if($admin->hasPermissionTo(68))
                                        <button class="layui-btn layui-btn-xs" title="回款" onclick="xadmin.open('回款记录','{{ route('admin.orders.back',['id'=>$list->id]) }}',1100,600);" ><i class="layui-icon">&#xe65e;</i>回款</button>
                                        @endif
                                        @if($admin->hasPermissionTo(82))
                                        <button class="layui-btn layui-btn-xs" title="退款" onclick="xadmin.open('退款记录','{{ route('admin.orders.refund',['id'=>$list->id,'plannedamt'=>$list->plannedamt]) }}',800,600);" ><i class="layui-icon">&#xe65e;</i>退款</button>
                                        @endif
                                        @if($admin->hasPermissionTo(66) && in_array($list->status, [1,5]))
                                        <a class="layui-btn-danger layui-btn layui-btn-xs" title="删除" onclick="info_del('{{ route('admin.orders.destroy', $list->id) }}','{{ csrf_token() }}')">
                                          <i class="layui-icon">&#xe640;</i>删除
                                        </a>
                                        @endif
                                        @if($admin->hasPermissionTo(102))
                                        <button class="layui-btn layui-btn-xs layui-btn-warm" title="终止" onclick="xadmin.open('订单状态变更','{{ route('admin.orders.stop', $list->id) }}',800,350);" ><i class="layui-icon">&#x1006;</i>@if($list->status == 0)终止@elseif($list->status == 3)待审核@elseif($list->status == 4)审核通过@elseif($list->status == 5)审核未通过@endif</button>
                                        @endif
                                    @endif
                                    @if($admin->hasPermissionTo(105))
                                    <button class="layui-btn layui-btn-xs layui-btn-normal" title="成本" onclick="xadmin.open('成本控制','{{ route('admin.orders.jscost', $list->id) }}',800,450);" ><i class="layui-icon">&#xe65e;</i>成本</button>
                                    @endif
                                @endif
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
<script type="text/javascript">
$(function(){
    var tips;
    $('.waverify').on({
        mouseenter:function(){
            var that = this;
            if($(this).attr('value')) tips =layer.tips("<span>"+$(this).attr('value')+"</span>",that,{tips:[2,'#009688'],time:0,area: 'auto',maxWidth:500});
        },
        mouseleave:function(){
            layer.close(tips);
        }
    });
    $('.jsverify').on({
        mouseenter:function(){
            var that = this;
            if($(this).attr('value')) tips =layer.tips("<span>"+$(this).attr('value')+"</span>",that,{tips:[2,'#009688'],time:0,area: 'auto',maxWidth:500});
        },
        mouseleave:function(){
            layer.close(tips);
        }
    });
    $('.process').on({
        mouseenter:function(){
            var that = this;
            if($(this).attr('value')) tips =layer.tips("<span>"+$(this).attr('value')+"</span>",that,{tips:[2,'#009688'],time:0,area: 'auto',maxWidth:500});
        },
        mouseleave:function(){
            layer.close(tips);
        }
    });
    $('.remarks').on({
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
        elem: '#licence_start',
        trigger: 'click'
    });

    //执行一个laydate实例
    laydate.render({
        elem: '#licence_end',
        trigger: 'click'
    });

    //执行一个laydate实例
    laydate.render({
        elem: '#analysidate',
        type: 'month', 
    });
});
</script>
@stop