@extends('admin.layout')

@section('title', '详情')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row layui-form-pane">

        <div class="layui-form-item">
            <label class="layui-form-label">客户名称:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->customer->company }}" disabled>
            </div>

            <label class="layui-form-label">对应订单编号:</label>
            <div class="layui-input-inline" style="width: 500px;">
                <input type="text" class="layui-input" value="@foreach(explode(',', $list->order_id) as $val){{ $order[$val] }}、@endforeach" disabled>
            </div>
        </div>
        <hr>
        @foreach(unserialize($list->device_msg) as $v)
        <div class="layui-form-item">
            <label for="good_id" class="layui-form-label">业务种类:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $v['good_id'] ? $good[$v['good_id']] : '' }}" disabled>
            </div>

            <label for="node_id" class="layui-form-label">节点名称:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $v['node_id'] ? $province[$v['node_id']] : '' }}" disabled>
            </div>

            <label for="device_number" class="layui-form-label">系统厂商:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $v['device_number'] ?: '' }}" disabled>
            </div>
        </div>

        <div class="layui-form-item">
            <label for="sn_number" class="layui-form-label">SN编号:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $v['sn_number'] ?: '' }}" disabled>
            </div>

            <label for="address" class="layui-form-label">收件地址:</label>
            <div class="layui-input-inline" style="width: 500px;">
                <input type="text" class="layui-input" value="{{ $v['address'] ?: '' }}" disabled>
            </div>
        </div>
        <hr>
        @endforeach
        <div class="layui-form-item">
            <label class="layui-form-label">合同起始时间:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->contract_starttime ? date('Y-m-d',$list->contract_starttime) : '' }}" disabled>
            </div>

            <label class="layui-form-label">合同结束时间:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->contract_endtime ? date('Y-m-d',$list->contract_endtime) : '' }}" disabled>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">信安金额:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->xinan_money }}" disabled>
            </div>

            <label class="layui-form-label">托管金额:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->deposit_money }}" disabled>
            </div>

            <label class="layui-form-label">升级金额:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->upgrade_money }}" disabled>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">备案金额:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->record_money }}" disabled>
            </div>

            <label class="layui-form-label">成本金额:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->cost_money }}" disabled>
            </div>

            <label class="layui-form-label">总金额:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->total }}" disabled>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">合同寄出时间:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->contract_send_time ? date('Y-m-d',$list->contract_send_time) : '' }}" disabled>
            </div>

            <label class="layui-form-label">合同寄回时间:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->contract_back_time ? date('Y-m-d',$list->contract_back_time) : '' }}" disabled>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">发票寄出时间:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->receipt_send_time ? date('Y-m-d',$list->receipt_send_time) : '' }}" disabled>
            </div>

            <label class="layui-form-label">付款时间:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->payment_time ? date('Y-m-d',$list->payment_time) : '' }}" disabled>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">创建人:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->user->nickname ?: $list->user->name }}" disabled>
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">维保备注:</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" disabled>{{ $list->remarks }}</textarea>
            </div>
        </div> 
        
    </div>
</div>
@stop