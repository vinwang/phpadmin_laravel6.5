@extends('admin.layout')

@section('title', '权限菜单')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form action="{{ route('admin.permissions.store') }}" method="POST" class="layui-form layui-form-pane valideform">
        	{{ csrf_field() }}
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    主菜单
                </label>
                <div class="layui-inline">
                    <select name="parent_id" lay-verify="">
                      <option value="0">请选择主菜单</option>
                      @foreach($list as $val)
                      <option value="{{ $val->id }}">{{ $val->name }}</option>
                      @foreach($val->permissions as $v)
                      <option value="{{ $v->id }}"> -- {{ $v->name }}</option>
                      @endforeach
                      @endforeach
                    </select> 
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    菜单名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="name" autocomplete="off" class="layui-input" value="{{ old('name') }}" datatype="*">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    菜单路由
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="uri" name="uri" autocomplete="off" class="layui-input" value="{{ old('uri') }}">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    图标
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="icon" name="icon" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    启用
                </label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="status" lay-skin="switch" lay-text="ON|OFF" value="1" checked>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    仅用于是否显示于左侧菜单
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    排序
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="sort" name="sort" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
            <button class="layui-btn" type="submit">提交</button>
          </div>
        </form>
    </div>
</div>
@stop