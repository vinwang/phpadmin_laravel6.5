@extends('admin.layout')

@section('title', '添加合同')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form layui-form-pane" method="POST" action="{{ route('admin.contract.store') }}" enctype="multipart/form-data" id="form">
            {{ csrf_field() }}

            <div class="layui-form-item">
                <label for="customer" class="layui-form-label"><span class="x-red">*</span>客户名称</label>
                <div class="layui-input-inline">
                    <div id="customer" class="xm-select-demo"></div>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="order" class="layui-form-label"><span class="x-red">*</span>对应销售订单</label>
                <div class="layui-input-inline">
                    <div id="order" class="xm-select-demo"></div>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="title" class="layui-form-label"><span class="x-red">*</span>@if($type)资质@else合同@endif标题</label>
                <div class="layui-input-inline">
                    <input type="text" id="title" name="title" autocomplete="off" class="layui-input" value="{{ old('title') }}">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="number" class="layui-form-label">@if($type)资质@else合同@endif编号</label>
                <div class="layui-input-inline">
                    <input type="text" id="number" name="number" autocomplete="off" class="layui-input" value="{{ old('number') }}">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="starttime" class="layui-form-label">开始时间</label>
                <div class="layui-input-inline">
                    <input type="text" id="starttime" name="starttime" autocomplete="off" class="layui-input" value="{{ old('starttime') }}">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="endtime" class="layui-form-label">截止时间</label>
                <div class="layui-input-inline">
                    <input type="text" id="endtime" name="endtime" autocomplete="off" class="layui-input" value="{{ old('endtime') }}">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="picture" class="layui-form-label">@if($type)上传资质@else图片@endif</label>
                <div class="layui-input-inline">
                    <input type="file" name="picture[]" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <a href="javascript:void(0);" title="添加图片" onclick="add_picture()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                </div>
            </div>

            <div class="layui-form-item" id="addpicture" style="display: none;"></div>
            @if(!$type)
                <div class="layui-form-item">
                    <label for="annex" class="layui-form-label">附件</label>
                    <div class="layui-input-inline">
                        <input type="file" name="annex[]" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <a href="javascript:void(0);" title="添加附件" onclick="add_annex()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                    </div>
                </div>

                <div id="addannex" style="display: none;"></div>
            @endif            
            <div class="layui-form-item layui-form-text">
                <label for="remark" class="layui-form-label">@if($type)资质@else合同@endif备注</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="remark" name="remark" class="layui-textarea">{{ old('remark') }}</textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="button" class="layui-btn" value="提交" onclick="addContract();">
            </div>
        </form>
    </div>
</div>
@stop
@section('javascript')
<script src="{{ asset('js/xm-select.js') }}" charset="utf-8"></script>
<script>
var order = xmSelect.render({
    el: "#order",
    style: {
        width: "400px"
    },
    name: "order",
    radio: true,
    filterable: true,
    clickClose: true,
    layVerify: "required",
    layVerType: "tips",
    data: []
});
var customer = xmSelect.render({
    el: "#customer",
    style: {
        width: "400px"
    },
    name: "customer",
    radio: true,
    filterable: true,
    clickClose: true,
    layVerify: "required",
    layVerType: "tips",
    data: @json($customer),
    on: function(selectValue){
        var uri = "{{ route('admin.contract.getorder') }}";
        var arr = selectValue.arr;
        var type = "{{ $type }}";
        $.ajax({
            type: "POST",
            url: uri,
            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            data: { customer: arr[0].value, type:type},
            dataType: "JSON",
            success: function(result){
                if(result.code == 0){
                    var orderData = result.data;
                    order.update({
                        template({item, sels, name, value}){
                            return item.name + '<span style="position: absolute; right: 10px; color: #8799a3">'+item.goods+'</span>';
                        },
                        data: orderData.data
                    });
                }
                else{
                    layer.msg(result.msg);
                }
            }
        });
    }
});
layui.use('layer', function(){
    @if(session('code') == '0')
        parent.window.location.reload();
    @elseif(session('code') == '1')
        layer.msg("{{ session('msg') }}");
        return false;
    @endif
});

layui.use(['laydate','form'], function() {
    var laydate = layui.laydate;
    var form=layui.form;

    //执行一个laydate实例
    laydate.render({
        elem: '#starttime',
        trigger: 'click'
    });

    //执行一个laydate实例
    laydate.render({
        elem: '#endtime',
        trigger: 'click'
    });
});

function addContract(){
    if($("#customer option:selected").val() == '')
    {
        layer.msg('请选择客户名称');
        return false;
    }
    if($("#order option:selected").val() == '')
    {
        layer.msg('请选择对应销售订单');
        return false;
    }
    if($('#title').val() == '')
    {
        layer.msg('请填写标题');
        return false;
    }
    $("#form").submit();
};

var image_num = 0;
var annex_num = 0;
function add_picture()
{
    image_num ++;
    $('#addpicture').before('<div class="layui-form-item" id="picture' + image_num +'">'
        +'<label for="picture' + image_num + '" class="layui-form-label">@if($type)上传资质@else图片@endif</label>'
        +'<div class="layui-input-inline">'
        +'<input type="file" name="picture[]" autocomplete="off" class="layui-input">'
        +'</div>'
        +'<div class="layui-form-mid layui-word-aux">'
        +'<a href="javascript:void(0);" title="删除图片" onclick="del_picture(' + image_num + ')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>'
        +'</div>'
        +'</div>'
    );
}

function del_picture(imagenum)
{
    var imagenum = imagenum;
    $('#picture' + imagenum).remove();
} 

function add_annex()
{
    annex_num ++;
    $('#addannex').before('<div class="layui-form-item" id="annex' + annex_num +'">'
        +'<label for="annex' + annex_num + '" class="layui-form-label">附件</label>'
        +'<div class="layui-input-inline">'
        +'<input type="file" name="annex[]" autocomplete="off" class="layui-input">'
        +'</div>'
        +'<div class="layui-form-mid layui-word-aux">'
        +'<a href="javascript:void(0);" title="删除附件" onclick="del_annex(' + annex_num + ')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>'
        +'</div>'
        +'</div>'
    );
}

function del_annex(annexnum)
{
    var annexnum = annexnum;
    $('#annex' + annexnum).remove();
} 
</script>
@stop