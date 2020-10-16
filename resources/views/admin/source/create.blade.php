@extends('admin.layout')

@section('title', '添加客户来源')

@section('content')

    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form layui-form-pane valideform" action="{{ route('admin.source.store')}}" method="POST">
                {{ csrf_field() }}
                <div class="layui-form-item">
                    <label for="phone" class="layui-form-label">
                        <span class="x-red">*</span>来源名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="name" name="name" autocomplete="off" class="layui-input" datatype="*">
                    </div>
                    <button class="layui-btn">增加</button>
                </div>
            </form>
        </div>
    </div>


@stop
