@extends('admin.layout')

@section('title', '提醒')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form action="#" method="POST" class="layui-form layui-form-pane valideform">
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    创建人
                </label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ auth('admin')->user()->name }}" disabled>
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label for="content" class="layui-form-label">
                    提醒内容
                </label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="content" name="content" class="layui-textarea" disabled="disabled">{{ $remind->content }}</textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    提醒时间
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="remind_time" autocomplete="off" class="layui-input" datatype="*" value="{{ $remind->remind_time }}" disabled>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    提醒成员
                </label>
                <div class="layui-inline">
                    <div id="users" class="xm-select-demo"></div>
                </div>
            </div>
            <!-- <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    提醒周期
                </label>
                <div class="layui-inline">
                    <div id="repeat" class="xm-select-demo"></div>
                </div>
            </div> -->
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    回访客户
                </label>
                <div class="layui-inline">
                    <div id="customer" class="xm-select-demo"></div>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('javascript')
<script src="{{ asset('js/xm-select.js') }}" charset="utf-8"></script>
<script type="text/javascript">
layui.use('laydate', function(){
var laydate = layui.laydate;
  laydate.render({
    elem: '#remind_time',
    type: 'datetime'
  });
});
xmSelect.render({
    el: "#users",
    style: {
        width: "188px"
    },
    name: "user",
    disabled: true,
    layVerify: "required",
    layVerType: "tips",
    data: @json($users)
}) 
/*xmSelect.render({
    el: "#repeat",
    style: {
        width: "188px"
    },
    radio: true,
    disabled: true,
    clickClose: true,
    model: {
        label: {
            type: 'text',
            text: {
                //左边拼接的字符
                left: '',
                //右边拼接的字符
                right: '',
                //中间的分隔符
                separator: ', ',
            },
        }
    },
    name: "repeat",
    layVerify: "required",
    layVerType: "tips",
    data: @json($repeat)
})*/
xmSelect.render({
    el: "#customer",
    style: {
        width: "188px"
    },
    name: "customer",
    disabled: true,
    layVerify: "required",
    layVerType: "tips",
    data: @json($customers)
})
</script>
@stop