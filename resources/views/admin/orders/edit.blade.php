@extends('admin.layout')

@section('title', '添加订单列表')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form layui-form-pane valideform-order" method="POST" action="{{ route('admin.orders.update',$list->id) }}">
            {{ csrf_field() }}
            @method('PUT') 
            <div class="layui-form-item">
                <label for="customer" class="layui-form-label"><span class="x-red">*</span>客户名称</label>
                <div class="layui-input-inline">
                    <select id="customer" name="customer" lay-search class="power" datatype="*" @if($role == 5) disabled @endif>
                        @foreach($customer as $key=>$val)
                            <option value="{{ $key }}" @if($list->customer_id == $key) selected @endif>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                @if($role == 4)
                @if($list->receivables->count())
                <div class="layui-input-inline">
                    <input type="checkbox" title="预付款后发货" name="shipped" lay-skin="primary" value="1" @if($list->shipped) checked @endif disabled>
                </div>
                @endif
                @else
                <div class="layui-input-inline">
                    <input type="checkbox" title="预付款后发货" name="shipped" lay-skin="primary" value="1" @if($list->shipped) checked @endif @if(!in_array($role, [1,2])) disabled @endif>
                </div>
                @endif
            </div> 
            
            @foreach($goodProvinces as $g=>$value)
            <div class="layui-form-item" id="goods{{ $loop->iteration }}">
                <label for="goods" class="layui-form-label"><span class="x-red">*</span>业务种类</label>
                <div class="layui-input-inline">
                    <select name="goods[]" class="power goods" lay-filter="goods" datatype="*" @if($role == 5 || ($list->jsverify != 3 && $list->jsverify != 2 && $list->waverify != 2 && $list->waverify != 3)) disabled @endif>
                        <option value=""></option>
                        @foreach($good as $key=>$val)
                            <option value="{{ $key }}" @if($g == $key) selected @endif>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="layui-input-inline">
                    <textarea name="citys[]" placeholder="请输入业务节点" class="layui-textarea citys power" datatype="*" @if($role == 5 || ($list->jsverify != 3 && $list->jsverify != 2 && $list->waverify != 2 && $list->waverify != 3)) disabled @endif>@foreach($value as $city){{ $city->citys  }}@endforeach
                    </textarea>
                </div>
                @if(!in_array($role, [3,4,5]) && ($list->jsverify == 3 || $list->jsverify == 2 || $list->waverify == 2 || $list->waverify == 3))
                <div class="layui-form-mid layui-word-aux">
                    @if($loop->iteration == 1)
                        <a href="javascript:void(0);" title="添加业务种类" onclick="add_goods()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                    @else
                        <a href="javascript:void(0);" title="删除业务种类" onclick="del_goods('{{ $loop->iteration }}')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>
                    @endif
                </div>
                @endif
                @if($loop->index == 0)
                <div class="layui-form-mid layui-word-aux">规范：上海市（评测）（含网）；<br>不评测或不含网则直接填写省、市；回车键换行，请用中文括号
                </div>
                @endif
            </div>  
            @endforeach

            <div id="addgoods" style="display: none;"></div> 
            @if($role != 3 && $role != 4)
            <div class="layui-form-item">
                <label for="plannedamt" class="layui-form-label"><span class="x-red">*</span>合同金额</label>
                <div class="layui-input-inline">
                    <input type="text" id="plannedamt" name="plannedamt" autocomplete="off" class="layui-input power" value="{{ $list->plannedamt }}" datatype="n" @if($role == 5) disabled @endif>
                </div>
            </div>
            
            @foreach($stagesarr as $val)
            <div class="layui-form-item" id="stages{{ $loop->iteration }}">
                <label for="stages" class="layui-form-label"><span class="x-red">*</span>第{{ $loop->iteration }}期金额</label>
                <div class="layui-input-inline">
                    <input type="text" name="stages[]" autocomplete="off" class="layui-input power stages" value="{{ $val['stages'] }}" datatype="n" @if($role == 5) disabled @endif>
                </div>
                <input type="hidden" id="stagesid{{ $key }}" value="{{ $val['stages'] }}" /> 
                @if($role != 5)
                <div class="layui-form-mid layui-word-aux">
                    @if($loop->iteration == 1)
                        <a href="javascript:void(0);" title="添加分期" onclick="add_stages()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                    @else
                        <a href="javascript:void(0);" title="删除分期" onclick="del_stages('{{ $loop->iteration }}')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>
                    @endif
                </div>
                @endif
                <input type="hidden" name="billing_time[]" value="{{ $val['billing_time'] }}">
                <input type="hidden" name="collection_time[]" value="{{ $val['collection_time'] }}">
            </div>
            @endforeach
            @endif
            <div id="addstages" style="display: none;"></div>
            
            @if($role !=3 && $role !=4)
            <div class="layui-form-item">
                <label for="business_cost" class="layui-form-label">商务费用(回扣)</label>
                <div class="layui-input-inline">
                    <input type="number" id="business_cost" name="business_cost" autocomplete="off" class="layui-input" value="{{ $list->cost ? $list->cost->business_cost : '' }}" @if($role == 5) disabled @endif>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="outsourcing_cost" class="layui-form-label">外包成本</label>
                <div class="layui-input-inline">
                    <input type="number" id="outsourcing_cost" name="outsourcing_cost" autocomplete="off" class="layui-input" value="{{ $list->cost ? $list->cost->outsourcing_cost : '' }}" @if($role == 5) disabled @endif>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="urgent_cost" class="layui-form-label">加急费用</label>
                <div class="layui-input-inline">
                    <input type="number" id="urgent_cost" name="urgent_cost" autocomplete="off" class="layui-input" value="{{ $list->cost ? $list->cost->urgent_cost : '' }}" @if($role == 5) disabled @endif>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="xiaoshou_other_cost" class="layui-form-label">其他成本</label>
                <div class="layui-input-inline">
                    <input type="number" id="xiaoshou_other_cost" name="xiaoshou_other_cost" autocomplete="off" class="layui-input" value="{{ $list->cost ? $list->cost->xiaoshou_other_cost : '' }}" @if($role == 5) disabled @endif>
                </div>
            </div>
            @endif
            
            <div class="layui-form-item layui-form-text">
                <label for="comments" class="layui-form-label">备注</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="comments" name="comments" class="layui-textarea power" @if($role == 5 || ($list->jsverify != 3 && $list->jsverify != 2 && $list->waverify != 2 && $list->waverify != 3)) disabled @endif>{{ $list->remarks }}</textarea>
                </div>
            </div>

            @if($role == 3 || $role == 1)
            <div class="layui-form-item">
                <label for="order_type" class="layui-form-label">订单类型</label>
                <div class="layui-input-inline">
                    <select id="order_type" name="order_type">
                        <option value="1" @if($list->order_type == 1) selected @endif>办证</option>
                        <option value="2" @if($list->order_type == 2) selected @endif>变更</option>
                        <option value="3" @if($list->order_type == 3) selected @endif>年审</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="increment" class="layui-form-label">增值服务</label>
                <div class="layui-input-inline">
                    <input type="text" id="increment" name="increment" autocomplete="off" class="layui-input" value="{{ $list->increment }}">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="licence" class="layui-form-label">许可证号</label>
                <div class="layui-input-inline">
                    <input type="text" id="licence" name="licence" autocomplete="off" class="layui-input" value="{{ $list->licence }}">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="licence_start" class="layui-form-label">证书起始时间</label>
                <div class="layui-input-inline">
                    <input type="text" id="licence_start" name="licence_start" autocomplete="off" class="layui-input" value="{{ $list->licence_start ? date('Y-m-d',$list->licence_start) : '' }}">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="licence_end" class="layui-form-label">证书截止时间</label>
                <div class="layui-input-inline">
                    <input type="text" id="licence_end" name="licence_end" autocomplete="off" class="layui-input" value="{{ $list->licence_end ? date('Y-m-d',$list->licence_end) : '' }}">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="process" class="layui-form-label"><span class="x-red">*</span>业务状态</label>
                <div class="layui-input-inline">
                    <select id="process" name="process" datatype="*">
                        @foreach($process as $key=>$val)
                            <option value="{{ $key }}" @if(in_array($key,$process_id)) selected @endif>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>    

            <div class="layui-form-item layui-form-text">
                <label for="remarks" class="layui-form-label">业务状态备注</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="remarks" name="remarks" class="layui-textarea">{{ $list->notes }}</textarea>
                </div>
            </div>
            @endif

            @if($role == 3 && $user->grade_id == 1)
                <div class="layui-form-item">
                    <label class="layui-form-label">审核状态:</label>
                    <div class="layui-input-inline">
                        <select id="waverify" name="waverify"> 
                            <option value="0" @if($list->waverify == 0) selected @endif>待审核</option>
                            <option value="1" @if($list->waverify == 1) selected @endif>审核通过</option>
                            <option value="2" @if($list->waverify == 2) selected @endif>审核未通过</option>
                            <option value="3" @if($list->waverify == 3) selected @endif>退回</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">审核备注:</label>
                    <div class="layui-input-block">
                        <textarea class="layui-textarea" name="wacontent">{{ $list->wa_content }}</textarea>
                    </div>
                </div>
            @endif

            @if($role == 4 && $user->grade_id == 1)
                <div class="layui-form-item">
                    <label class="layui-form-label">审核状态:</label>
                    <div class="layui-input-inline">
                        <select id="jsverify" name="jsverify"> 
                            <option value="0" @if($list->jsverify == 0) selected @endif>待审核</option>
                            <option value="1" @if($list->jsverify == 1) selected @endif>审核通过</option>
                            <option value="2" @if($list->jsverify == 2) selected @endif>审核未通过</option>
                            <option value="3" @if($list->jsverify == 3) selected @endif>退回</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">审核备注:</label>
                    <div class="layui-input-block">
                        <textarea class="layui-textarea" name="jscontent">{{ $list->js_content }}</textarea>
                    </div>
                </div>
            @endif

            @if($role != 5)
            <div class="layui-form-item">
                <label for="user_id" class="layui-form-label">流转</label>
                <div class="layui-input-inline">
                    <div id="user_id" class="xm-select-demo"></div>
                </div>
            </div>
            @endif
            
            <div class="layui-form-item">
                <button class="layui-btn" lay-filter="add" lay-submit="">提交</button>
            </div>
        </form>
    </div>
</div>
@stop
@section('javascript')
<script src="{{ asset('js/xm-select.js') }}" charset="utf-8"></script>
<script type="text/javascript">
layui.use(['form','layer','laydate'], function(){
    var form = layui.form;
    var laydate = layui.laydate;

    @if($role == 3 || $role == 4)
        $('.power').attr('disabled',true);
    @endif
    form.render();

    //执行一个laydate实例
    laydate.render({
        elem: '#licence_start',
        trigger: 'click'
    });

    //执行一个laydate实例
    laydate.render({
        elem: '#licence_end',
        trigger: 'click'
    });
    $(".valideform-order").Validform({
        tiptype: function(msg,o,cssctl){
            if(o.type == '3'){
                layer.msg(msg);
            }
        },
        tipSweep: true,
        // postonce: true, //二次提交防御
        ajaxPost: true,
        beforeCheck: function(curform){
            $(".power").prop("disabled", false);
        },
        callback: function(result){
            if(result.code > 0){
                layer.msg(result.msg);
                return false;
            }

            if(result.code == 0){
                xadmin.close();
            }
            if(result.data.uri != undefined){
                if(result.data.uri){
                    xadmin.add_tab(result.data.tabTitle, result.data.uri, 1, 2);
                    // parent.location.href = result.data.uri;
                }
                else{
                    parent.location.reload();
                }
            }

        }
    });
});

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
        +'<input type="hidden" name="billing_time[]" value="">'
        +'<input type="hidden" name="collection_time[]" value="">'
        +'</div>'
    );
}
               
function del_stages(stagesnum)
{
    var stagesnum = stagesnum;
    var stagesid = $('#stagesid' + stagesnum).val();
    if (stagesid)
    {
        $('#stages' + stagesnum).remove();
    }
    $('#stages' + stagesnum).remove();
} 

var goods_num = "{{ count($goodProvinces) }}";
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


@if($role != 5)
xmSelect.render({
    el: "#user_id",
    style: {
        width: "188px"
    },
    name: "user_id",
    layVerType: "tips",
    data: @json($username)
})
@endif
</script>
@stop