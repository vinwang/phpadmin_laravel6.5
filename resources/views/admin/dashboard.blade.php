@extends('admin.layout')

@section('title', 'Dashboard')

@section('body_class', 'index')

@section('content')

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">

        <div class="layui-col-sm12 layui-col-md4">
            <div class="layui-card">
                <div class="layui-card-header">最新一周新增客户</div>
                <div class="layui-card-body" style="min-height: 280px;">
                    <div id="main1" class="layui-col-sm12" style="height: 300px;"></div>

                </div>
            </div>
        </div>
        <div class="layui-col-sm12 layui-col-md4">
            <div class="layui-card">
                <div class="layui-card-header">最新一周新增订单</div>
                <div class="layui-card-body" style="min-height: 280px;">
                    <div id="main2" class="layui-col-sm12" style="height: 300px;"></div>

                </div>
            </div>
        </div>
        <div class="layui-col-sm12 layui-col-md4">
            <div class="layui-card">
                <div class="layui-card-header">最新一周新增订单金额</div>
                <div class="layui-card-body" style="min-height: 280px;">
                    <div id="main5" class="layui-col-sm12" style="height: 300px;"></div>

                </div>
            </div>
        </div>
        <div class="layui-col-sm12 layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">客户来源</div>
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
                data: ['周一','周二','周三','周四','周五','周六','周日']
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
                name:'新增用户量',
                data: [{{ $custom_count }}],
                type: 'line',
                smooth: true
            }]
        };


        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);

        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main2'));

        option = {
            color: ['#3398DB'],
            tooltip : {
                trigger: 'axis',
                axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                    type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
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
                    data : ['周一','周二','周三','周四','周五','周六','周日'],
                    axisTick: {
                        alignWithLabel: true
                    }
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
                    name:'新增订单量',
                    type:'bar',
                    barWidth: '60%',
                    data:[{{$order_count}}]
                }
            ]
        };

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main5'));

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
                    data : ['周一','周二','周三','周四','周五','周六','周日']
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
                    name:'新增订单金额数',
                    type:'line',
                    areaStyle: {normal: {}},
                    data:[{{$order_money}}],
                    smooth: true
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
                data: [<?php echo $source_name;?>]
            },
            series : [
                {
                    name: '新增来源',
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data:[
                        @foreach($source_num as $val)
                            {value:{{$val[0]}}, name:'{{$val[1]}}'},
                        @endforeach
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
                    data: [{value: {{$disk_use}}, name: '已使用'}]
                }
            ]
        };
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
@stop