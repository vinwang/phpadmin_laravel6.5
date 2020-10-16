@extends('admin.layout')

@section('title', '客户标签编辑')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form layui-form-pane valideform" action="{{ route('admin.tags.store') }}" method="POST">
            {{ csrf_field() }}
           <div class="layui-form-item">
                <label for="phone" class="layui-form-label">
                    <span class="x-red">*</span>标签名
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="tagname" name="tagname" autocomplete="off" class="layui-input" datatype="*">
                </div>
                <button class="layui-btn" lay-filter="add" lay-submit="">增加</button>
            </div>
        </form>
    </div>
</div>
@stop
