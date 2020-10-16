@extends('admin.layout')

@section('title', '操作记录')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">业务节点状态记录</li>
    </ul>
    <div class="layui-row layui-form-pane layui-tab-content">
        <div class="layui-tab-item layui-show">
            @foreach($list as $value)
             <ul class="layui-timeline">
                <li class="layui-timeline-item">
                    <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                    <div class="layui-timeline-content layui-text">
                        <h3 class="layui-timeline-title">{{ $value->updated_at }}</h3>
                        <p> 
                            {{ $value->content }}@if($value->remarks) ({{ $value->remarks }}) @endif
                        </p>
                        <p>操作人:{{ $user[$value->user_id] }}</p>
                    </div>
                </li>
            </ul>
            @endforeach
        </div>
    </div>
</div>
@stop
@section('javascript')
<script>
layui.use('element', function(){
  var element = layui.element;
});
</script>
@stop