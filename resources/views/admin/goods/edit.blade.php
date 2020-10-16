@extends('admin.layout')

@section('title', '编辑业务种类')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form layui-form-pane valideform" method="POST" action="{{ route('admin.goods.update',$list->id) }}">
            {{ csrf_field() }}
            @method('PUT')
            <div class="layui-form-item">
                <label for="name" class="layui-form-label"><span class="x-red">*</span>业务种类名称</label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="name" datatype="*" autocomplete="off" class="layui-input" value="{{ $list->name }}">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="status" class="layui-form-label"><span class="x-red">*</span>是否激活</label>
                <div class="layui-input-inline">
                    <div class="layui-input-inline">
                    <input type="checkbox" name="status" lay-skin="switch" lay-text="开启|关闭" value="1" @if($list->status == 1) checked @endif>
                </div>
                </div>
            </div>
                    
            <div class="layui-form-item layui-form-text">
                <label for="description" class="layui-form-label">描述</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="description" name="description" class="layui-textarea">{{ $list->description }}</textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn" lay-filter="add" lay-submit="">编辑</button>
            </div>
        </form>
    </div>
</div>
@stop
