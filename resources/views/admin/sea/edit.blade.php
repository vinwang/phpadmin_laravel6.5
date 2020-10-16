@extends('admin.layout')

@section('title', 'Dashboard')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
  <div class="layui-row">
     <form class="layui-form valideform layui-form-pane" action="{{ route('admin.sea.update',$data->id) }}" method="post">
     @method('PUT') 
        <div class="layui-form-item">
            <label for="source_id" class="layui-form-label">
                <span class="x-red">*</span>分配对象
            </label>
            <div class="layui-inline ">
                <select name="uid">  
                   @foreach($users as $arr)
                   <option value="{{ $arr->id }}">{{ $arr->name }}</option>
                  @endforeach 
                </select>
            </div>
            <input type="submit" name="" value="分配" class="layui-btn" lay-filter="edit" lay-submit="">
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}"> 
        <input type="hidden" name="id" value="{{ $data->id}}">
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
