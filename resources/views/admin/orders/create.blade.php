@extends('admin.layout')

@section('title', '添加订单')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form layui-form-pane valideform" method="POST" action="{{ route('admin.orders.store') }}">
            {{ csrf_field() }}

            <div class="layui-form-item">
                <label for="customer" class="layui-form-label"><span class="x-red">*</span>客户名称</label>
                <div class="layui-input-inline">
                    <select id="customer" name="customer" lay-search datatype="*">
                        <option value=""></option>
                        @foreach($customer as $key=>$val)
                            <option value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="layui-input-inline">
                    <input type="checkbox" title="预付款后发货" name="shipped" autocomplete="off" lay-skin="primary" checked value="1">
                </div>
            </div> 
            
            <div class="layui-form-item">
                <label for="goods" class="layui-form-label"><span class="x-red">*</span>业务种类</label>
                <div class="layui-input-inline">
                    <select name="goods[]" class="goods" lay-filter="goods" datatype="*">
                        <option value=""></option>
                        @foreach($good as $key=>$val)
                            <option value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="layui-input-inline">
                    <textarea name="citys[]" placeholder="请输入业务节点" class="layui-textarea citys" datatype="*"></textarea>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <a href="javascript:void(0);" title="添加业务种类" onclick="add_goods()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                </div>
                <div class="layui-form-mid layui-word-aux">规范：上海市（评测）（含网）；<br>不评测或不含网则直接填写省、市；回车键换行，请用中文括号
                </div>
            </div>

            <div id="addgoods" style="display: none;"></div>
            
            <div class="layui-form-item">
                <label for="plannedamt" class="layui-form-label"><span class="x-red">*</span>合同金额</label>
                <div class="layui-input-inline">
                    <input type="text" id="plannedamt" name="plannedamt" autocomplete="off" class="layui-input" value="" datatype="n">
                </div>
            </div>
            
            <div class="layui-form-item">
                <label for="stages" class="layui-form-label"><span class="x-red">*</span>第1期金额</label>
                <div class="layui-input-inline">
                    <input type="text" name="stages[]" autocomplete="off" class="layui-input stages" value="" datatype="n">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <a href="javascript:void(0);" title="添加分期" onclick="add_stages()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                </div>
            </div>

            <div id="addstages" style="display: none;"></div>

            <div class="layui-form-item">
                <label for="business_cost" class="layui-form-label">商务费用(回扣)</label>
                <div class="layui-input-inline">
                    <input type="number" id="business_cost" name="business_cost" autocomplete="off" class="layui-input" value="">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="outsourcing_cost" class="layui-form-label">外包成本</label>
                <div class="layui-input-inline">
                    <input type="number" id="outsourcing_cost" name="outsourcing_cost" autocomplete="off" class="layui-input" value="">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="urgent_cost" class="layui-form-label">加急费用</label>
                <div class="layui-input-inline">
                    <input type="number" id="urgent_cost" name="urgent_cost" autocomplete="off" class="layui-input" value="">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="xiaoshou_other_cost" class="layui-form-label">其他成本</label>
                <div class="layui-input-inline">
                    <input type="number" id="xiaoshou_other_cost" name="xiaoshou_other_cost" autocomplete="off" class="layui-input" value="">
                </div>
            </div>
               
            <div class="layui-form-item layui-form-text">
                <label for="remarks" class="layui-form-label">备注</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="remarks" name="remarks" class="layui-textarea"></textarea>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="user_id" class="layui-form-label">流转</label>
                <div class="layui-input-inline">
                    <div id="user_id" class="xm-select-demo"></div>
                </div>
            </div>

            <div class="layui-col-md12">
                <button class="layui-btn" lay-filter="add" lay-submit="">提交</button>
            </div> 
        </form>
    </div>
</div>
@stop
@section('javascript')
<script src="{{ asset('js/xm-select.js') }}" charset="utf-8"></script>
<script type="text/javascript">
var goods_num = 0;
function add_stages()
{
    var stages_num = $('.stages').length;
    stages_num ++;
    $('#addstages').before('<div class="layui-form-item" id="stages' + stages_num +'">'
        +'<label for="stages" class="layui-form-label"><span class="x-red">*</span>第'+ stages_num +'期金额</label>'
        +'<div class="layui-input-inline">'
        +'<input type="text" name="stages[]" autocomplete="off" class="layui-input stages" value="" datatype="n">'
        +'</div>'
        +'<div class="layui-form-mid layui-word-aux">'
        +'<a href="javascript:void(0);" title="删除分期" onclick="del_stages(' + stages_num + ')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>'
        +'</div>'
        +'</div>'
    );
}
               
function del_stages(stagesnum)
{
    var stagesnum = stagesnum;
    $('#stages' + stagesnum).remove();
} 

function add_goods()
{
    layui.use(['form','layer','laydate'], function(){
        var form = layui.form;
        goods_num ++;
        $('#addgoods').before('<div class="layui-form-item" id="goods' + goods_num +'">'
            +'<label for="goods" class="layui-form-label"><span class="x-red">*</span>业务种类</label>'
            +'<div class="layui-input-inline">'
            +'<select name="goods[]" class="goods" lay-filter="goods" datatype="*">'
            +'<option value=""></option>'
            +'@foreach($good as $key=>$val)'
            +'<option value="{{ $key }}">{{ $val }}</option>'
            +'@endforeach'
            +'</select>'
            +'</div>'
            +'<div class="layui-input-inline">'
            +'<textarea name="citys[]" placeholder="请输入业务节点" class="layui-textarea citys" datatype="*"></textarea>'
            +'</div>'
            +'<div class="layui-form-mid layui-word-aux">'
            +'<a href="javascript:void(0);" title="删除业务种类" onclick="del_goods(' + goods_num + ')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>'
            +'</div>'
            +'</div>'
        );
        form.render();
    });
}
              
function del_goods(goodsnum)
{
    var goodsnum = goodsnum;
    $('#goods' + goodsnum).remove();
}

xmSelect.render({
    el: "#user_id",
    style: {
        width: "188px"
    },
    name: "user_id",
    layVerType: "tips",
    data: @json($username)
})
</script>
@stop