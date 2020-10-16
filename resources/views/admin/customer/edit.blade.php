@extends('admin.layout')

@section('title', 'Dashboard')

@section('body_class', 'index')

@section('content')
<link rel="stylesheet" href="{{ asset('css/formSelects-v4.css') }}">
    <div class="layui-fluid">
    <div class="layui-row">
       <form class="layui-form valideform layui-form-pane" action="{{ route('admin.customer.update',$data->id) }}" method="post">
       @method('PUT')
                  <div class="layui-form-item">
                      <label for="company" class="layui-form-label">
                          <span class="x-red">*</span>公司名称
                      </label>
                      <div class="layui-input-inline">
                          <input type="text" id="company" name="company" value="{{ $data->company}}" datatype="*" nullmsg="公司名称不能为空"
                          autocomplete="off" class="layui-input">
                      </div>
                      <div class="layui-form-mid layui-word-aux">
                          <span class="x-red">*</span>请填写公司名称
                      </div>
                  </div>
                  <div class="layui-form-item">
                      <label for="address" class="layui-form-label">
                          <span class="x-red"></span>公司地址
                      </label>
                      <div class="layui-input-inline">
                          <input type="text" id="address" name="address" value="{{ $data->address}}"
                          autocomplete="off" class="layui-input">
                      </div>
                  </div>
                  <div class="layui-form-item">
                      <label for="source_id" class="layui-form-label">
                          <span class="x-red">*</span>客户来源
                      </label>
                      <div class="layui-inline layui-show-xs-block">
                          <select name="source_id"> 

                            <option value="0">无</option>
                             @foreach($res as $arr)
                             <option value="{{ $arr->id }}" @if( $data->source_id == $arr->id ) selected @endif>{{ $arr->name }}</option>
                            @endforeach 
                          </select>
                      </div>
                  </div>
                  <div class="layui-form-item">
                      <label for="tags_id" class="layui-form-label">
                          客户标签
                      </label>
                      <div class="layui-input-inline">
                          <div id="tags_id" class="xm-select-demo"></div>
                      </div>
                  </div>
                  <div class="layui-form-item">
                      <label for="name" class="layui-form-label">
                          <span class="x-red">*</span>联系人姓名
                      </label>
                      <div class="layui-input-inline">
                          <input type="text" id="name" name="name" value="{{ $data->name}}" datatype="*" nullmsg="联系人姓名不能为空"
                          autocomplete="off" class="layui-input">
                      </div>
                      <div class="layui-form-mid layui-word-aux">
                          <span class="x-red">*</span>请填写联系人姓名
                      </div>
                  </div>
                  <div class="layui-form-item">
                      <label for="phone" class="layui-form-label">
                          <span class="x-red">*</span>联系人手机号
                      </label>
                      <div class="layui-input-inline">
                          <input type="text" id="phone" name="phone" value="{{ $data->phone}}" datatype="*" nullmsg="联系人手机号不能为空"
                          autocomplete="off" class="layui-input">
                      </div>
                      <div class="layui-form-mid layui-word-aux">
                          <span class="x-red">*</span>请填写联系人手机号
                      </div>
                  </div>
                  <div class="layui-form-item">
                      <label for="tel" class="layui-form-label">
                        公司座机
                      </label>
                      <div class="layui-input-inline">
                          <input type="text" id="tel" name="tel" value="{{ $data->tel}}"
                          autocomplete="off" class="layui-input">
                      </div>
                      
                  </div>
                  <div class="layui-form-item">
                      <label for="emil" class="layui-form-label">
                        邮箱
                      </label>
                      <div class="layui-input-inline">
                          <input type="text" id="emil" name="emil" value="{{ $data->emil}}"
                          autocomplete="off" class="layui-input">
                      </div>
                      <div class="layui-form-mid layui-word-aux">

                      </div>
                  </div>
                  <div class="layui-form-item">
                      <label for="job" class="layui-form-label">
                          职务
                      </label>
                      <div class="layui-input-inline">
                          <input type="text" id="job" name="job" value="{{ $data->job}}"
                          autocomplete="off" class="layui-input">
                      </div>
                      <div class="layui-form-mid layui-word-aux">

                      </div>
                  </div>

                  <div class="layui-form-item">
                      <label for="username" class="layui-form-label">用户名</label>
                      <div class="layui-input-inline">
                          <input type="text" id="username" name="username" value="{{ $data->username}}" autocomplete="off" class="layui-input">
                      </div>
                  </div>

                  <div class="layui-form-item">
                      <label for="password" class="layui-form-label">密码</label>
                      <div class="layui-input-inline">
                          <input type="text" id="password" name="password" value="{{ $data->password}}" autocomplete="off" class="layui-input">
                      </div>
                  </div>
          
                  <div class="layui-form-item">
                      <label for="phone" class="layui-form-label">
                          <span class="x-red">*</span>是否分配
                      </label>
                      <div class="layui-inline layui-show-xs-block">
                          <select name="status"> 
                            <option value="1" @if( $data->status == 1 ) selected @endif>已分配</option>
                        <option value="0" @if( $data->status == 0 ) selected @endif>未分配</option>
                          </select>
                      </div>
                  </div>
                 <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">备注:</label>
                    <div class="layui-input-block">
                        <textarea class="layui-textarea" id="remarks" name="remarks">{{$data->remarks}}</textarea>
                    </div>
                  </div>
                  <div class="layui-form-item"> 
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <input type="submit" name="" value="修改" class="layui-btn" lay-filter="edit" lay-submit="">
                  </div>
                  <input type="hidden" name="id" value="{{ $data->id}}">
              </form>
    </div>
</div>
@stop
@section('javascript') 
<script src="{{ asset('js/xm-select.js') }}" charset="utf-8"></script>
<script type="text/javascript">
layui.use(['form', 'layer'],function() {
    $ = layui.jquery;
    var form = layui.form,
        layer = layui.layer;
});
xmSelect.render({
  el: "#tags_id",
  style: {
      width: "188px"
  },
  name: "tags_id",
  data: @json($tags)
})
</script> 
@stop
