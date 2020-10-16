@extends('admin.layout')

@section('title', '编辑对接订单')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form layui-form-pane valideform" method="POST" action="{{ route('admin.abutment.update',$list->id) }}">
            {{ csrf_field() }}
            @method('PUT')
            <div class="layui-form-item">
                <label for="customer" class="layui-form-label"><span class="x-red">*</span>客户名称</label>
                <div class="layui-input-inline">
                    <input type="text" id="customer_id" name="customer_id" autocomplete="off" class="layui-input" value="{{ $list->customer->company }}" readonly>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="order_id" class="layui-form-label"><span class="x-red">*</span>对应订单编号</label>
                <div class="layui-input-inline">
                    <div id="order_id" class="xm-select-demo"></div>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="good_id" class="layui-form-label"><span class="x-red">*</span>业务种类</label>
                <div class="layui-input-inline">
                    <div id="good_id" class="xm-select-demo"></div>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="node_id" class="layui-form-label"><span class="x-red">*</span>节点名称</label>
                <div class="layui-input-inline">
                    <div id="node_id" class="xm-select-demo"></div>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="starttime" class="layui-form-label">对接开始时间</label>
                <div class="layui-input-inline">
                    <input type="text" id="starttime" name="starttime" autocomplete="off" class="layui-input" value="{{ $list->starttime ? date('Y-m-d',$list->starttime) : '' }}">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="endtime" class="layui-form-label">对接结束时间</label>
                <div class="layui-input-inline">
                    <input type="text" id="endtime" name="endtime" autocomplete="off" class="layui-input" value="{{ $list->endtime ? date('Y-m-d',$list->endtime) : '' }}">
                </div>
            </div>

            <div class="layui-form-item layui-form-text">
                <label for="remark" class="layui-form-label">对接备注</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="remark" name="remark" class="layui-textarea">{{ $list->remark }}</textarea>
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
<script src="{{ asset('js/xm-select.js') }}" charset="utf-8"></script>
<script>
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
var order = xmSelect.render({
    el: "#order_id",
    style: {
        width: "400px"
    },
    name: "order_id",
    radio: true,
    filterable: true,
    clickClose: true,
    layVerify: "required",
    layVerType: "tips",
    data: @json($order),
    on: function(selectValue){
        var uri = "{{ route('admin.abutment.getgood') }}";
        var arr = selectValue.arr;
        $.ajax({
            type: "GET",
            url: uri,
            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            data: { order: arr[0].value },
            dataType: "JSON",
            success: function(result){
                if(result.code == 0){
                    var goodData = result.data;
                    good.update({
                        template({item, sels, name, value}){
                            return item.name;
                        },
                        data: goodData.data
                    });
                }
                else{
                    layer.msg(result.msg);
                }
            }
        });
    }
});
var good = xmSelect.render({
    el: "#good_id",
    style: {
        width: "400px"
    },
    name: "good_id",
    radio: true,
    filterable: true,
    clickClose: true,
    layVerify: "required",
    layVerType: "tips",
    data: @json($good),
    on: function(selectValue){
        var uri = "{{ route('admin.abutment.getnode') }}";
        var arr = selectValue.arr;
        var order_id = order.getValue('valueStr');
        $.ajax({
            type: "GET",
            url: uri,
            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            data: { good: arr[0].value, order_id:order_id },
            dataType: "JSON",
            success: function(result){
                if(result.code == 0){
                    var nodeData = result.data;
                    node.update({
                        template({item, sels, name, value}){
                            return item.name;
                        },
                        data: nodeData.data
                    });
                }
                else{
                    layer.msg(result.msg);
                }
            }
        });
    }
});
var node = xmSelect.render({
    el: "#node_id",
    style: {
        width: "400px"
    },
    name: "node_id",
    radio: true,
    filterable: true,
    clickClose: true,
    layVerify: "required",
    layVerType: "tips",
    data: @json($node)
});
</script>
@stop