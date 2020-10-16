@extends('admin.layout')

@section('title', '等级编辑')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form layui-form-pane valideform" action="{{ route('admin.grade.store') }}" method="POST">
            {{ csrf_field() }}
           <div class="layui-form-item">
                <label for="phone" class="layui-form-label">
                    用户等级
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="name" autocomplete="off" class="layui-input" datatype="*">

                </div>
                <button class="layui-btn" lay-filter="add" lay-submit="">提交</button>
            </div>
        </form>
    </div>
</div>
@stop
