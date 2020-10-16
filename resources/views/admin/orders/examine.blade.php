@extends('admin.layout')

@section('title', '审核退款')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form valideform layui-form-pane" method="POST" action="{{ route('admin.orders.examine') }}">
            {{ csrf_field() }}
            <div class="layui-form-item">
                <label class="layui-form-label">是否通过:</label>
                <div class="layui-input-inline">
                    <select id="status" name="status"> 
                        <option value="0">待审核</option>
                        <option value="1">审核通过</option>
                        <option value="2">审核未通过</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">审核备注:</label>
                <div class="layui-input-block">
                    <textarea class="layui-textarea" name="reason"></textarea>
                </div>
            </div>  
            <input type="hidden" name="id" value="{{ $id }}">
            <button class="layui-btn" lay-filter="add" lay-submit="">提交</button>
        </form>
    </div>
</div>
@stop
