@extends('admin.layout')

@section('title', '对接订单详情')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row layui-form-pane">


        <div class="layui-form-item">
            <label class="layui-form-label">客户名称:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->customer->company }}" disabled>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">对应订单编号:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->order->order_num }}" disabled>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">业务种类:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->good->name }}" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">节点名称:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->province->name }}" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">对接开始时间:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->starttime ? date('Y-m-d',$list->starttime) : '' }}" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">对接结束时间:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->endtime ? date('Y-m-d',$list->endtime) : '' }}" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">创建人:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->user->nickname ?: $list->user->name }}" disabled>
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">对接备注:</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" disabled>{{ $list->remark }}</textarea>
            </div>
        </div> 
        
    </div>
</div>
@stop