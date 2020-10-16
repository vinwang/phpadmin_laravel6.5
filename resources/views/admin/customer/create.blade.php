@extends('admin.layout')

@section('title', 'Dashboard')

@section('body_class', 'index')

@section('content')
  <link rel="stylesheet" href="{{ asset('css/formSelects-v4.css') }}">
    <div class="layui-fluid">
    <div class="layui-row">
       <form class="layui-form layui-col-space5 valideform layui-form-pane" method="POST" action="{{ route('admin.customer.store') }}">
       {{ csrf_field() }} 
          <div class="layui-form-item">
              <label for="company" class="layui-form-label">
                  <span class="x-red">*</span>公司名称
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="company" name="company" datatype="*" nullmsg="公司名称不能为空"
                  autocomplete="off" class="layui-input">
              </div>
              <div class="layui-form-mid layui-word-aux">
                  <span class="x-red">*</span>请填写公司名称  例：xxx有限公司
              </div>
          </div>
          <div class="layui-form-item">
              <label for="address" class="layui-form-label">
                  <span class="x-red"></span>公司地址
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="address" name="address"
                  autocomplete="off" class="layui-input">
              </div>
          </div>
          <div class="layui-form-item">
              <label for="phone" class="layui-form-label">
                  <span class="x-red">*</span>客户来源
              </label>
              <div class="layui-inline layui-show-xs-block">
                  <select name="source_id"> 
                    @foreach($res as $arr)
                    <option value="{{ $arr->id }}">{{ $arr->name }}</option>
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
              <label for="phone" class="layui-form-label">
                  <span class="x-red">*</span>联系人姓名
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="name" name="name" datatype="*" nullmsg="联系人姓名不能为空"
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
                  <input type="text" id="phone" name="phone" datatype="*" nullmsg="联系人手机号不能为空"
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
                  <input type="text" id="tel" name="tel" 
                  autocomplete="off" class="layui-input">
              </div>
          </div>
          <div class="layui-form-item">
              <label for="emil" class="layui-form-label">
                  邮箱
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="emil" name="emil" 
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
                  <input type="text" id="job" name="job" 
                  autocomplete="off" class="layui-input">
              </div>
              <div class="layui-form-mid layui-word-aux">

              </div>
          </div>
          
          <div class="layui-form-item">
              <label for="username" class="layui-form-label">用户名</label>
              <div class="layui-input-inline">
                  <input type="text" id="username" name="username" autocomplete="off" class="layui-input">
              </div>
          </div>

          <div class="layui-form-item">
              <label for="password" class="layui-form-label">密码</label>
              <div class="layui-input-inline">
                  <input type="text" id="password" name="password" autocomplete="off" class="layui-input">
              </div>
          </div>

          <div class="layui-form-item">
              <label for="phone" class="layui-form-label">
                  <span class="x-red">*</span>是否分配
              </label>
              <div class="layui-inline layui-show-xs-block">
                  <select name="status"> 
                    <option value="1">已分配</option>
                    <option value="0">未分配</option>
                  </select>
              </div>
          </div> 
          <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">备注:</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" id="remarks" name="remarks"></textarea>
            </div>
          </div>
        
          <div class="layui-form-item"> 
              <input type="submit" name="" value="添加" class="layui-btn" lay-filter="add" lay-submit="">
          </div> 
      </form>
    </div>
</div>
@stop
@section('javascript') 
<script src="{{ asset('js/xm-select.js') }}" charset="utf-8"></script>
<script type="text/javascript">
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
