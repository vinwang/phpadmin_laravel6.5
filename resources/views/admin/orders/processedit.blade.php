@extends('admin.layout')

@section('title', '客户标签编辑')

@section('body_class', 'index')

@section('content')
<link rel="stylesheet" href="{{ asset('css/formSelects-v4.css') }}">
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form layui-form-pane valideform" action="{{ route('admin.orders.processedit',['lid'=>$id]) }}" method="POST">
            {{ csrf_field() }}
           <div class="layui-form-item">
                <label for="process" class="layui-form-label"><span class="x-red">*</span>业务状态</label>
                <div class="layui-input-inline" style="width: 500px;">
                    <select id="process" name="process" xm-select="select16_6">
                        @foreach($jsprocess as $key=>$val)
                            <option value="{{ $key }}" @if(in_array($key,explode(',',$list->process))) selected @endif>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>    

            <div class="layui-form-item layui-form-text">
                <label for="content" class="layui-form-label">业务状态退回原因</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="content" name="content" class="layui-textarea">{{ $list->content }}</textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn" lay-filter="add" lay-submit="">提交</button>
            </div>
        </form>
    </div>
</div>
@stop
@section('javascript')
<script src="{{ asset('js/formSelects-v4.min.js') }}" charset="utf-8"></script>
@stop