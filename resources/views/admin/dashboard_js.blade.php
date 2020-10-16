@extends('admin.layout')

@section('title', 'Dashboard')

@section('body_class', 'index')

@section('content')
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">我的桌面</a>
        <a><cite>订单成本分析</cite></a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
    </a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <form class="layui-form" action="#" method="get">
            <div class="layui-col-sm2">
                <input type="text" class="layui-input" id="date" autocomplete="off" placeholder="选择时间" name="date" value="{{ request('date') }}">
            </div>
            @if($admin->grade_id == 1 && $roles == 4)
            <div class="layui-col-sm2">
                <select name="user_id">
                    <option value="">请选择归属人</option>
                    @foreach($data['js_put_id'] as $val)
                      <option value="{{ $val->id }}" {{ (request('user_id') == $val->id ? 'selected':'') }}>{{ $val->nickname ?: $val->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <button class="layui-btn"  lay-submit="" lay-filter="sreach" style="margin-left:5px;">查询</button>
        </form>
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">订单成本统计</div>
                <div class="layui-card-body ">
                    <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                        <li class="layui-col-md4">
                            <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index',['analysidate'=>request('date'), 'userId' => request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                <h3>流转订单总量</h3>
                                <p><cite>{{ $data['allOrders_count'] }}</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md4">
                            <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index',['hktype'=>'11', 'analysidate'=>request('date'), 'userId' => request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                <h3>完成订单总量</h3>
                                <p><cite>{{ $data['finish_count']}}</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md4">
                            <a onclick="xadmin.add_tab('订单列表', '{{ route('admin.orders.index',['hktype'=>'12', 'analysidate'=>request('date'), 'userId' => request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                <h3>成本总额</h3>
                                <p><cite>￥{{ number_format($data['cost_allmoney']) }}</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md4">
                            <a onclick="xadmin.add_tab('维保订单列表', '{{ route('admin.maintenance.index',['hktype'=>'1', 'date'=>request('date'), 'userId' => request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                <h3>维保订单托管费用</h3>
                                <p><cite>￥{{ number_format($data['maintenance']['depositMoney']) }}</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md4">
                            <a onclick="xadmin.add_tab('维保订单列表', '{{ route('admin.maintenance.index',['hktype'=>'2', 'date'=>request('date'), 'userId' => request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                <h3>维保订单成本费用</h3>
                                <p><cite>￥{{ number_format($data['maintenance']['costMoney']) }}</cite></p>
                            </a>
                        </li>
                        <li class="layui-col-md4">
                            <a onclick="xadmin.add_tab('维保订单列表', '{{ route('admin.maintenance.index',['hktype'=>'3', 'date'=>request('date'), 'userId' => request('user_id')]) }}', 1, 1)" class="x-admin-backlog-body" href="javascript:;">
                                <h3>维保订单总金额</h3>
                                <p><cite>￥{{ number_format($data['maintenance']['total']) }}</cite></p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="layui-col-sm12 layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">最新一月流转订单量</div>
                <div class="layui-card-body" style="min-height: 280px;">
                    <div id="main1" class="layui-col-sm12" style="height: 300px;"></div>

                </div>
            </div>
        </div>
        <div class="layui-col-sm12 layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">最新一月完成订单量</div>
                <div class="layui-card-body" style="min-height: 280px;">
                    <div id="main2" class="layui-col-sm12" style="height: 300px;"></div>

                </div>
            </div>
        </div>
        <div class="layui-col-sm12 layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">最新一月新增成本金额</div>
                <div class="layui-card-body" style="min-height: 280px;">
                    <div id="main3" class="layui-col-sm12" style="height: 300px;"></div>

                </div>
            </div>
        </div>
        <div class="layui-col-sm12 layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">硬盘使用量</div>
                <div class="layui-card-body" style="min-height: 280px;">
                    <div id="main4" class="layui-col-sm12" style="height: 300px;"></div>

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
            data: [{{ $data['month'] }}]
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
            name:'新增订单量',
            data: [{{ $data['order_count'] }}],
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
                data : [{{ $data['month'] }}]
            }
        ],
        yAxis : [
            {
                type : 'value',
                minInterval : 1,
                boundaryGap : [ 0, 0.1 ],
                axisLabel : {
                    formatter: '{value} 个'
                }
            }
        ],
        series : [
            {
                name:'完成订单量',
                type:'line',
                areaStyle: {normal: {}},
                data:[{{ $data['order_finish_count'] }}],
                smooth: true,

            }
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);

    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main3'));

    // 指定图表的配置项和数据
    var option = {
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data: ['设备成本','托管费用','技术其他成本','软件成本']
        },
        series : [
            {
                name: '新增成本',
                type: 'pie',
                radius : '55%',
                center: ['50%', '60%'],
                data:[
                    {value:{{ $data['cost']['equipmentCost'] ?: 0 }}, name:'设备成本'},
                    {value:{{ $data['cost']['trusteeshipCost'] ?: 0 }}, name:'托管费用'},
                    {value:{{ $data['cost']['jishuOtherCost'] ?: 0 }}, name:'技术其他成本'},
                    {value:{{ $data['cost']['softwareCost'] ?: 0 }}, name:'软件成本'},
                ],
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);

     // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main4'));

    // 指定图表的配置项和数据
    var option = {
        tooltip : {
            formatter: "{a} <br/>{b} : {c}%"
        },
        series: [
            {
                name: '硬盘使用量',
                type: 'gauge',
                detail: {formatter:'{value}%'},
                data: [{value: {{ $data['disk_use'] }}, name: '已使用'}]
            }
        ]
    };
    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
</script>
@stop