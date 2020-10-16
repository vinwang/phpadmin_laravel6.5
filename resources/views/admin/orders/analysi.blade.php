@extends('admin.layout')

@section('title', 'Dashboard')

@section('body_class', 'index')

@section('title', '订单分析')

@section('content')

    <div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="">首页</a>
            <a href="">订单管理</a>
            <a><cite>订单分析</cite></a>
        </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
        </a>
    </div>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space10">
            <form class="layui-form" action="#" method="get">
                <div class="layui-col-sm2">
                    <input type="text" class="layui-input" id="date" autocomplete="off" placeholder="选择时间" name="date" value="{{ request('date') }}">
                </div>
                <div class="layui-col-sm2">
                    <select id="customer_id" name="customer_id" lay-search datatype="*">
                        <option value="">请选择客户</option>
                        @foreach($customers as $val)
                            <option value="{{ $val->id }}" {{ (request('customer_id') == $val->id ? 'selected':'') }}>{{ $val->company }}</option>
                        @endforeach
                    </select>
                </div>
                @if($adminRole == 2 && $admin->grade_id != 1)
                @else
                <div class="layui-col-sm2">
                    <select id="user_id" name="user_id" lay-search datatype="*">
                        <option value="">请选择销售</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ (request('user_id') == $user->id ? 'selected':'') }}>{{ $user->nickname ?: $user->name  }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <button class="layui-btn"  lay-submit="" lay-filter="sreach" style="margin-left:5px;">查询</button>
            </form>
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">回款统计</div>
                    <div class="layui-card-body ">
                        <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index',['hktype'=>'1', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>回款次数</h3>
                                    <p><cite>{{ $receivablesCount }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index',['hktype'=>'1', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>回款金额</h3>
                                    <p><cite>￥{{ number_format($receivables) }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('客户列表', '{{ route('admin.customer.index',['hktype'=>'1']) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>回款的客户</h3>
                                    <p><cite>{{ $receivablesCustomers }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index',['hktype'=>'1', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>回款的订单</h3>
                                    <p><cite>{{ $receivablesOrdersCount }}</cite></p>
                                </a>
                            </li> 
                        </ul>
                    </div>
                </div> 
                <div class="layui-card">
                    <div class="layui-card-header">订单分析</div>
                    <div class="layui-card-body ">
                        <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index', ['hktype'=>'0', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>销售订单数</h3>
                                    <p><cite>{{ $ordersCount }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index',['hktype'=>'2', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>已完成的订单数</h3>
                                    <p><cite>{{ $ordersFinishedCount }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index',['hktype'=>'3', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>未完成的订单数</h3>
                                    <p><cite>{{ $ordersUnfinishedCount }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index', ['hktype'=>'0', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>订单总额</h3>
                                    <p><cite>￥{{ number_format($ordersAmount) }}</cite></p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="layui-card">
                    <div class="layui-card-header">成本统计</div>
                    <div class="layui-card-body ">
                        <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index', ['hktype'=>'4', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>商务费用(回扣)</h3>
                                    <p><cite>￥{{ number_format($cost->businessCost) }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index', ['hktype'=>'5', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>外包成本</h3>
                                    <p><cite>￥{{ number_format($cost->outsourcingCost) }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index', ['hktype'=>'6', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>加急费用</h3>
                                    <p><cite>￥{{ number_format($cost->urgentCost) }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index', ['hktype'=>'7', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>其他成本</h3>
                                    <p><cite>￥{{ number_format($cost->xiaoshouOtherCost) }}</cite></p>
                                </a>
                            </li>
                            @if(in_array($adminRole, [1,4,5]) || ($adminRole == 2 && $admin->grade->id == 1))
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index', ['hktype'=>'8', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>设备成本</h3>
                                    <p><cite>￥{{ number_format($cost->equipmentCost) }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index', ['hktype'=>'9', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>托管费用</h3>
                                    <p><cite>￥{{ number_format($cost->trusteeshipCost) }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index', ['hktype'=>'9', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>软件成本</h3>
                                    <p><cite>￥{{ number_format($cost->softwareCost) }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index', ['hktype'=>'10', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>技术部其他成本</h3>
                                    <p><cite>￥{{ number_format($cost->jishuOtherCost) }}</cite></p>
                                </a>
                            </li>
                            @endif
                            <!-- <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index') }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>利润金额</h3>
                                    <p><cite>￥{{ number_format($cost->businessCost) }}</cite></p>
                                </a>
                            </li> -->
                        </ul>
                    </div>
                </div>
                <div class="layui-card">
                    <div class="layui-card-header">财务统计</div>
                    <div class="layui-card-body ">
                        <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index', ['hktype'=>'13', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>已开票金额</h3>
                                    <p><cite>￥{{ number_format($kpamount) }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index', ['hktype'=>'14', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>已收款金额</h3>
                                    <p><cite>￥{{ number_format($skamount) }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md3 layui-col-xs6">
                                <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index', ['hktype'=>'15', 'analysidate'=>request('date'), 'customer_id'=>request('customer_id'), 'user_id'=>request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                    <h3>未收款总额</h3>
                                    <p><cite>￥{{ number_format($wskamount) }}</cite></p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div> 
            <div class="layui-row layui-col-space15">

        <div class="layui-col-sm12 layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">最新一月订单量</div>
                <div class="layui-card-body" style="min-height: 280px;">
                    <div id="main1" class="layui-col-sm12" style="height: 300px;"></div>

                </div>
            </div>
        </div>
        <div class="layui-col-sm12 layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">最新一月订单金额</div>
                <div class="layui-card-body" style="min-height: 280px;">
                    <div id="main2" class="layui-col-sm12" style="height: 300px;"></div>

                </div>
            </div>
        </div> 
    </div>
            </div>
            </div>
        </div>
    </div> 
@stop

@section('javascript')
<script src="{{ asset('js/xadmin.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/echarts.min.js') }}"></script>
<script type="text/javascript">
layui.use('laydate', function(){
    var laydate = layui.laydate;

    //执行一个laydate实例
    laydate.render({
        elem: '#date',
        type: 'month', 
    });
});

// 基于准备好的dom，初始化echarts实例
var myChart = echarts.init(document.getElementById('main1'));

// 指定图表的配置项和数据
var option = {
    grid: {
        top: '5%',
        right: '1%',
        left: '1%',
        bottom: '10%',
        containLabel: true
    },
    tooltip: {
        trigger: 'axis'
    },
    xAxis: {
        type: 'category',
        data: [{{ $month }}]
    },
    yAxis: {
        type: 'value',
        minInterval : 1,
        boundaryGap : [ 0, 0.1 ],
        axisLabel : {
            formatter: '{value} 个'
        }
    },
    series: [{
        name:'订单量',
        data: [{{ $order_count }}],
        type: 'line',
        smooth: true
    }]
};


// 使用刚指定的配置项和数据显示图表。
myChart.setOption(option);

// 基于准备好的dom，初始化echarts实例
var myChart = echarts.init(document.getElementById('main2'));

// 指定图表的配置项和数据
var option = {
    tooltip : {
        trigger: 'axis',
        axisPointer: {
            type: 'cross',
            label: {
                backgroundColor: '#6a7985'
            }
        }
    },
    grid: {
        top: '5%',
        right: '2%',
        left: '1%',
        bottom: '10%',
        containLabel: true
    },
    xAxis : [
        {
            type : 'category',
            boundaryGap : false,
            data : [{{$month}}]
        }
    ],
    yAxis : [
        {
            type : 'value',
            minInterval : 1,
            boundaryGap : [ 0, 0.1 ],
            axisLabel : {
                formatter: '{value} 元'
            }
        }
    ],
    series : [
        {
            name:'订单金额',
            type:'line',
            areaStyle: {normal: {}},
            data:[{{ $order_money }}],
            smooth: true,

        }
    ]
};

// 使用刚指定的配置项和数据显示图表。
myChart.setOption(option);
</script>
@stop
