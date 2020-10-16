@extends('admin.layout')

@section('title', 'Dashboard')

@section('body_class', 'index')

@section('content')

<div class="container">
    <div class="logo">
        <a href="{{ route('admin.index') }}">{{ config('app.name') }}</a></div>
    <div class="left_open">
        <a><i title="展开左侧栏" class="iconfont">&#xe699;</i></a>
    </div>
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;">{{ auth('admin')->user()->name }}</a>
            <dl class="layui-nav-child">
                <!-- 二级菜单 -->
                <dd>
                    <a onclick="xadmin.open('个人信息','{{ route('admin.users.edit', $admin->id) }}',900,600)">个人信息</a>
                </dd>
                <dd>
                    <form action="{{ route('admin.logout') }}" method="POST" id="logout">
                            {{ csrf_field() }}
                    <a href="javascript:;" onclick="$('#logout').submit()">退出</a>
                    </form>
                </dd>
            </dl>
        </li>
    </ul>
</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<div class="left-nav">
    <div id="side-nav">
        <ul id="nav">
            @foreach($superMenu as $menu)
            <li>
                <a href="javascript:;">
                    <i class="iconfont left-nav-li" lay-tips="{{ $menu['name'] }}">
                    &#xe6a7;
                    </i>
                    <cite>{{ $menu['name'] }}</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                @if($menu['menus'])
                <ul class="sub-menu">
                    @foreach($menu['menus'] as $sub)
                    <li>
                        <a href="javascript:;" onclick="xadmin.add_tab('{{ isset($sub['alias']) ? $sub['alias'] : $sub['name'] }}', '{{ $sub['uri'] }}')">
                            <i class="iconfont">
                                &#xe6a7;
                            </i>
                            <cite>{{ $sub['name'] }}</cite>
                            <!-- <i class="iconfont nav_right">&#xe697;</i> -->
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
            
        </ul>
    </div>
</div>
<!-- <div class="x-slide_left"></div> -->
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <ul class="layui-tab-title">
            <li class="home">
                <i class="layui-icon">&#xe68e;</i>我的桌面</li></ul>
        <div class="layui-unselect layui-form-select layui-form-selected" id="tab_right">
            <dl>
                <dd data-type="this">关闭当前</dd>
                <dd data-type="other">关闭其它</dd>
                <dd data-type="all">关闭全部</dd></dl>
        </div>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src="{{ route('admin.dashboard') }}" frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
        </div>
        <div id="tab_show"></div>
    </div>
</div>

<div class="page-content-bg"></div>
<!-- 右侧主体结束 -->
@stop
@section('javascript')
<script type="text/javascript">
var interval = null;
$(function(){
    // start();
})

function start(){
    if(interval != null){
        clearInterval(interval);
        interval = null;
    }
    interval = setInterval(remind, 5000);
}

function remind(){
    $.ajax({
        url: "{{ route('admin.doremind') }}",
        dataType: "JSON",
        success: function(result){
            if(result.code == 0){
                var data = result.data;
                for(var i in data){
                    if(data[i].content == undefined){
                        return false;
                    }

                    var html = '<div class="layui-fluid">';
                    if(data[i].customers != undefined){
                        html += '<div class="layui-card-body">客户：'+data[i].customers+'</div>';
                        html += '<hr>';
                    }
                    
                    html += '<div class="layui-card-body">'+data[i].content+'</div></div>';
                    var dataUrl = data[i].url;
                    layer.open({
                      type: 1
                      ,offset: 'rb'
                      ,area: ['300px', '400px']
                      ,content: html
                      ,btn: '确认'
                      ,btnAlign: 'c' //按钮居中
                      ,shade: 0 //不显示遮罩
                      ,anim:2
                      ,yes: function(index, layero){ 
                        layer.close(index);
                        if(dataUrl != undefined){
                            xadmin.add_tab('订单列表', dataUrl, 1, 1);
                        }
                      }
                    });
                }
            }
        }
    })
}
</script>

@stop
