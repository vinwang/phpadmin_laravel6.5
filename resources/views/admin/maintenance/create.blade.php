@extends('admin.layout')

@section('title', '添加维保订单')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form layui-form-pane valideform" method="POST" action="{{ route('admin.maintenance.store') }}">
            {{ csrf_field() }}
            <div class="layui-form-item">
                <label for="customer_id" class="layui-form-label"><span class="x-red">*</span>客户名称</label>
                <div class="layui-input-inline">
                    <div id="customer_id" class="xm-select-demo"></div>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="order_id" class="layui-form-label"><span class="x-red">*</span>对应销售订单</label>
                <div class="layui-input-inline">
                    <div id="order_id" class="xm-select-demo"></div>
                </div>
            </div>

            <hr>
            <div class="layui-form-item">
                <label for="good_id" class="layui-form-label"><span class="x-red">*</span>业务种类</label>
                <div class="layui-input-inline">
                    <div id="good_id" class="xm-select-demo"></div>
                </div>

                <label for="node_id" class="layui-form-label"><span class="x-red">*</span>节点名称</label>
                <div class="layui-input-inline">
                    <div id="node_id" class="xm-select-demo"></div>
                </div>

                <label for="device_number" class="layui-form-label">系统厂商</label>
                <div class="layui-input-inline">
                    <input type="text" id="device_number" name="device_number[]" autocomplete="off" class="layui-input" value="">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="sn_number" class="layui-form-label">SN编号</label>
                <div class="layui-input-inline">
                    <input type="text" id="sn_number" name="sn_number[]" autocomplete="off" class="layui-input" value="">
                </div>

                <label for="address" class="layui-form-label">收件地址</label>
                <div class="layui-input-inline" style="width: 500px;">
                    <input type="text" id="address" name="address[]" autocomplete="off" class="layui-input" value="">
                </div>

                <div class="layui-form-mid layui-word-aux">
                    <a href="javascript:void(0);" title="添加设备" onclick="add_maintenance()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                </div>
            </div>

            <div id="addmaintenance" style="display: none;"></div>
            <hr>

            <div class="layui-form-item">
                <label for="contract_starttime" class="layui-form-label">合同起始时间</label>
                <div class="layui-input-inline">
                    <input type="text" id="contract_starttime" name="contract_starttime" autocomplete="off" class="layui-input" value="">
                </div>

                <label for="contract_endtime" class="layui-form-label">合同结束时间</label>
                <div class="layui-input-inline">
                    <input type="text" id="contract_endtime" name="contract_endtime" autocomplete="off" class="layui-input" value="">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="xinan_money" class="layui-form-label">信安金额</label>
                <div class="layui-input-inline">
                    <input type="number" id="xinan_money" name="xinan_money" autocomplete="off" class="layui-input txt_input" value="" datatype="n" ignore="ignore">
                </div>

                <label for="deposit_money" class="layui-form-label">托管金额</label>
                <div class="layui-input-inline">
                    <input type="number" id="deposit_money" name="deposit_money" autocomplete="off" class="layui-input txt_input" value="" datatype="n" ignore="ignore">
                </div>

                <label for="upgrade_money" class="layui-form-label">升级金额</label>
                <div class="layui-input-inline">
                    <input type="number" id="upgrade_money" name="upgrade_money" autocomplete="off" class="layui-input txt_input" value="" datatype="n" ignore="ignore">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="record_money" class="layui-form-label">备案金额</label>
                <div class="layui-input-inline">
                    <input type="number" id="record_money" name="record_money" autocomplete="off" class="layui-input txt_input" value="" datatype="n" ignore="ignore">
                </div>
                
                <label for="cost_money" class="layui-form-label">成本金额</label>
                <div class="layui-input-inline">
                    <input type="number" id="cost_money" name="cost_money" autocomplete="off" class="layui-input" value="" datatype="n" ignore="ignore">
                </div>

                <label for="total" class="layui-form-label">总金额</label>
                <div class="layui-input-inline">
                    <input type="number" id="total" name="total" autocomplete="off" class="layui-input" value="" datatype="n" ignore="ignore">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="contract_send_time" class="layui-form-label">合同寄出时间</label>
                <div class="layui-input-inline">
                    <input type="text" id="contract_send_time" name="contract_send_time" autocomplete="off" class="layui-input" value="">
                </div>

                <label for="contract_back_time" class="layui-form-label">合同寄回时间</label>
                <div class="layui-input-inline">
                    <input type="text" id="contract_back_time" name="contract_back_time" autocomplete="off" class="layui-input" value="">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="receipt_send_time" class="layui-form-label">发票寄出时间</label>
                <div class="layui-input-inline">
                    <input type="text" id="receipt_send_time" name="receipt_send_time" autocomplete="off" class="layui-input" value="">
                </div>

                <label for="payment_time" class="layui-form-label">付款时间</label>
                <div class="layui-input-inline">
                    <input type="text" id="payment_time" name="payment_time" autocomplete="off" class="layui-input" value="">
                </div>
            </div>

            <div class="layui-form-item layui-form-text">
                <label for="remarks" class="layui-form-label">维保备注</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="remarks" name="remarks" class="layui-textarea"></textarea>
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
        elem: '#contract_starttime',
        trigger: 'click'
    });

    //执行一个laydate实例
    laydate.render({
        elem: '#contract_endtime',
        trigger: 'click'
    });
    //执行一个laydate实例
    laydate.render({
        elem: '#contract_send_time',
        trigger: 'click'
    });

    //执行一个laydate实例
    laydate.render({
        elem: '#contract_back_time',
        trigger: 'click'
    });
    //执行一个laydate实例
    laydate.render({
        elem: '#receipt_send_time',
        trigger: 'click'
    });

    //执行一个laydate实例
    laydate.render({
        elem: '#payment_time',
        trigger: 'click'
    });
});
var customer = xmSelect.render({
    el: "#customer_id",
    style: {
        width: "400px"
    },
    name: "customer_id",
    radio: true,
    filterable: true,
    clickClose: true,
    layVerify: "required",
    layVerType: "tips",
    data: @json($customer),
    on: function(selectValue){
        var uri = "{{ route('admin.contract.getorder') }}";
        var arr = selectValue.arr;
        $.ajax({
            type: "POST",
            url: uri,
            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            data: { customer: arr[0].value, type:"3"},
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
var order = xmSelect.render({
    el: "#order_id",
    style: {
        width: "400px"
    },
    name: "order_id",
    filterable: true,
    layVerify: "required",
    layVerType: "tips",
    data: [],
});
var good = xmSelect.render({
    el: "#good_id",
    style: {
        width: "181px"
    },
    name: "good_id[]",
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
        width: "181px"
    },
    name: "node_id[]",
    radio: true,
    filterable: true,
    clickClose: true,
    layVerify: "required",
    layVerType: "tips",
    data: []
});
var maintenance_num = 0;
function add_maintenance()
{
    layui.use(['form'], function(){
        var form = layui.form;
        maintenance_num ++;
        $('#addmaintenance').before('<div id="maintenance'+ maintenance_num +'"><div class="layui-form-item">'
                +'<label for="good_id" class="layui-form-label"><span class="x-red">*</span>业务种类</label>'
                +'<div class="layui-input-inline">'
                    +'<div id="good_id'+ maintenance_num +'" class="xm-select-demo"></div>'
                +'</div>'

                +'<label for="node_id" class="layui-form-label"><span class="x-red">*</span>节点名称</label>'
                +'<div class="layui-input-inline">'
                    +'<div id="node_id'+ maintenance_num +'" class="xm-select-demo"></div>'
                +'</div>'

                +'<label for="device_number" class="layui-form-label">系统厂商</label>'
                +'<div class="layui-input-inline">'
                    +'<input type="text" name="device_number[]" autocomplete="off" class="layui-input" value="">'
                +'</div>'
            +'</div>'

            +'<div class="layui-form-item">'
                +'<label for="sn_number" class="layui-form-label">SN编号</label>'
                +'<div class="layui-input-inline">'
                    +'<input type="text" name="sn_number[]" autocomplete="off" class="layui-input" value="">'
                +'</div>'

                +'<label for="address" class="layui-form-label">收件地址</label>'
                +'<div class="layui-input-inline" style="width: 500px;">'
                    +'<input type="text" name="address[]" autocomplete="off" class="layui-input" value="">'
                +'</div>'

                +'<div class="layui-form-mid layui-word-aux">'
                    +'<a href="javascript:void(0);" title="删除设备" onclick="del_maintenance(' + maintenance_num + ')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>'
                +'</div>'
            +'</div></div>'
        );
        
        var good = xmSelect.render({
            el: "#good_id"+ maintenance_num,
            style: {
                width: "181px"
            },
            name: "good_id[]",
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
                console.log(order_id);
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
            el: "#node_id"+ maintenance_num,
            style: {
                width: "181px"
            },
            name: "node_id[]",
            radio: true,
            filterable: true,
            clickClose: true,
            layVerify: "required",
            layVerType: "tips",
            data: []
        });

        form.render();
    });
}
              
function del_maintenance(maintenancenum)
{
    var maintenancenum = maintenancenum;
    $('#maintenance' + maintenancenum).remove();
}

$(function(){
    $(".txt_input").blur(function(){
        var i=0;
        $.each($(".txt_input"),function(n,item){
            i+=parseInt($(item).val(),10);
        });
        $("#total").val(i);
    }).keyup(function(){
       $(this).val($(this).val().replace(/[^\d.]/gi,""));
    })
})
</script>
@stop