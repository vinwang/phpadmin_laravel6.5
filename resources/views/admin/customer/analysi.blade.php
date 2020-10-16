@extends('admin.layout')

@section('title', '系统设置')

@section('content')

    <div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="">首页</a>
            <a href="">客户管理</a>
            <a><cite>客户分析</cite></a>
        </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
        </a>
    </div>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space10">
            <form class="layui-form" action="#" method="get">
                <div class="layui-col-sm2">
                    <input type="text" class="layui-input" id="date" name="date">
                </div>
                <button class="layui-btn"  lay-submit="" lay-filter="sreach" style="margin-left:5px;">查询</button>
            </form>
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">客户分析</div>
                    <div class="layui-card-body ">
                        <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                            <li class="layui-col-md2 layui-col-xs6">
                                <a onclick="xadmin.add_tab('客户列表', '{{ route('admin.customer.index',['method'=>1,'date'=>$date]) }}', 1, 1)" class="x-admin-backlog-body">
                                    <h3>客户总数</h3>
                                    <p><cite>{{ $month_add_customer_count }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6">
                                <a onclick="xadmin.add_tab('客户列表', '{{ route('admin.customer.index',['method'=>2]) }}', 1, 1)" class="x-admin-backlog-body">
                                    <h3>已回访的客户</h3>
                                    <p><cite>{{ $mount_visit_customer_count }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6">
                                <a onclick="xadmin.add_tab('客户列表', '{{ route('admin.customer.index',['method'=>3]) }}', 1, 1)" class="x-admin-backlog-body">
                                    <h3>跟进的客户</h3>
                                    <p><cite>{{ $mount_with_customer_count }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6">
                                <a onclick="xadmin.add_tab('客户列表', '{{ route('admin.customer.index',['method'=>4,'date'=>$date]) }}', 1, 1)" class="x-admin-backlog-body">
                                    <h3>未及时跟进的客户</h3>
                                    <p><cite>{{ $mount_no_with_customer_count }}</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6">
                                <a onclick="xadmin.add_tab('客户列表', '{{ route('admin.customer.index',['method'=>5]) }}', 1, 1)" class="x-admin-backlog-body">
                                    <h3>成交的客户</h3>
                                    <p><cite>{{ $mount_deal_customer_count }}</cite></p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="layui-row layui-col-space15">
                <div class="layui-col-sm12 layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header">本月新增客户数</div>
                        <div class="layui-card-body" style="min-height: 280px;">
                            <div id="main1" class="layui-col-sm12" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
                <!-- <div class="layui-col-sm12 layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header">本月新客户回款金额</div>
                        <div class="layui-card-body" style="min-height: 280px;">
                            <div id="main2" class="layui-col-sm12" style="height: 300px;"></div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>

@stop

@section('javascript')
<script src="{{ asset('js/echarts.min.js') }}"></script>
<script>
layui.use('laydate', function(){
    var laydate = layui.laydate;

    //执行一个laydate实例
    laydate.render({
        elem: '#date',
        type: 'month',
        format: 'yyyy-MM',
        value: '{{ $date }}'
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
        boundaryGap : false,
        data: [{{ $days }}],
    },
    yAxis: {
        type: 'value',
        minInterval : 1,
        boundaryGap : [ 0, 0.1 ],
        axisLabel : {
            formatter: '{value} 位'
        }
    },
    series: [{
        name:'新增客户',
        data: [{{ $add_count }}],
        type: 'line',
        smooth: true
    }]
};


// 使用刚指定的配置项和数据显示图表。
myChart.setOption(option);

</script>
@stop
