@extends('admin.layout')

@section('title', 'Dashboard')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
  <div class="layui-row">
     <form class="layui-form layui-col-space5 valideform" method="POST" action="{{ route('admin.bank.store') }}">
     {{ csrf_field() }}  
        <div class="layui-form-item">
            <label for="bankname" class="layui-form-label">
                <span class="x-red">*</span>开户行名称
            </label>
            <div class="layui-input-inline">
                <input type="text" id="bankname" name="bankname" datatype="*" lay-verify="bankname"
                autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>请填写开户行名称
            </div>
        </div>
        <div class="layui-form-item">
            <label for="banknumber" class="layui-form-label">
                <span class="x-red">*</span>开户行账号
            </label>
            <div class="layui-input-inline">
                <input type="text" id="banknumber" name="banknumber" datatype="*" lay-verify="banknumber"
                autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>请填写开户行账号
            </div>
        </div> 
        <div class="layui-form-item">
            <label for="bankadd" class="layui-form-label">
                <span class="x-red">*</span>开户行所属地
            </label>
            <div class="layui-input-inline">
                <input type="text" id="bankadd" name="bankadd" datatype="*" lay-verify="bankadd"
                autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>请填写开户行所属地和支行名称
            </div>
        </div>
        <div class="layui-form-item">
            <label for="companycode" class="layui-form-label">
                <span class="x-red">*</span>统一社会信用代码
            </label>
            <div class="layui-input-inline">
                <input type="text" id="companycode" name="companycode" datatype="*" lay-verify="companycode"
                autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>请填写公司统一信用代码
            </div>
        </div>
       
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <input type="submit" name="" value="添加" class="layui-btn" lay-filter="add" lay-submit="">
            <input type="hidden" name="id" value="{{$id}}">
        </div>
    </form>
  </div>
</div>
@stop
@section('javascript')
<script>
layui.use(['form', 'layer'],function() {
    $ = layui.jquery;
    var form = layui.form,
        layer = layui.layer;
});
</script> 
@stop
