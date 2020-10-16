@extends('admin.layout')

@section('title', '添加订单列表')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form layui-form-pane" method="POST" action="{{ route('admin.orders.update',$list->id) }}" enctype="multipart/form-data" id="form">
            {{ csrf_field() }}
            @method('PUT') 
            <div class="layui-form-item">
                <label for="customer" class="layui-form-label"><span class="x-red">*</span>客户名称</label>
                <div class="layui-input-inline">
                    <select id="customer" name="customer" lay-search class="power" @if($role == 5) disabled @endif>
                        @foreach($customer as $key=>$val)
                            <option value="{{ $key }}" @if($list->customer_id == $key) selected @endif>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div> 
            
            @foreach($goodProvince as $g=>$value)
            <div class="layui-form-item" id="goods{{ $loop->iteration }}">
                <label for="goods" class="layui-form-label"><span class="x-red">*</span>业务种类</label>
                <div class="layui-input-inline">
                    <select name="goods[]" class="power goods" lay-filter="goods" @if($role == 5) disabled @endif>
                        <option value=""></option>
                        @foreach($good as $key=>$val)
                            <option value="{{ $key }}" @if($g == $key) selected @endif>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="layui-input-inline">
                    <div id="province{{ $loop->iteration }}" class="xm-select-demo power"></div>
                </div>
                <div class="layui-input-inline">
                    <div id="city{{ $loop->iteration }}" class="xm-select-demo power"></div>
                </div>
                @if($role == 3 || $role == 4 || $role == 5)
                @else
                <div class="layui-form-mid layui-word-aux">
                    @if($loop->iteration == 1)
                        <a href="javascript:void(0);" title="添加业务种类" onclick="add_goods()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                    @else
                        <a href="javascript:void(0);" title="删除业务种类" onclick="del_goods('{{ $loop->iteration }}')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>
                    @endif
                </div>
                @endif
            </div>  
            @endforeach

            <div id="addgoods" style="display: none;"></div> 
            @if($role !=3 && $role !=4)
            <div class="layui-form-item">
                <label for="plannedamt" class="layui-form-label"><span class="x-red">*</span>合同金额</label>
                <div class="layui-input-inline">
                    <input type="text" id="plannedamt" name="plannedamt" autocomplete="off" class="layui-input power" value="{{ $list->plannedamt }}" @if($role == 5) disabled @endif>
                </div> 
            </div>
            
            @foreach($stagesinfo[0] as $val)
            <div class="layui-form-item" id="stages{{ $loop->iteration }}">
                <label for="stages" class="layui-form-label"><span class="x-red">*</span>第{{ $loop->iteration }}期金额</label>
                <div class="layui-input-inline">
                    <input type="text" name="stages[]" autocomplete="off" class="layui-input power stages" value="{{ $val }}" @if($role == 5) readonly @endif>
                </div>
                <input type="hidden" id="stagesid{{ $key }}" value="{{ $val }}" /> 
                @if($role == 3 || $role == 4 || $role == 5)
                @else
                <div class="layui-form-mid layui-word-aux">
                    @if($loop->iteration == 1)
                        <a href="javascript:void(0);" title="添加分期" onclick="add_stages()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                    @else
                        <a href="javascript:void(0);" title="删除分期" onclick="del_stages('{{ $loop->iteration }}')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>
                    @endif
                </div>
                @endif
                @if($role == 5 || $role == 1)
                    <label for="billing_time" class="layui-form-label">开票时间{{ $loop->iteration }}</label>
                    <div class="layui-input-inline">
                        <input type="text" id="billing_time{{ $loop->iteration }}" name="billing_time[]" autocomplete="off" class="layui-input">
                    </div>

                    <label for="collection_time" class="layui-form-label">收款时间{{ $loop->iteration }}</label>
                    <div class="layui-input-inline">
                        <input type="text" id="collection_time{{ $loop->iteration }}" name="collection_time[]" autocomplete="off" class="layui-input">
                    </div>
                @endif
            </div>
            @endforeach
            @endif
            <div id="addstages" style="display: none;"></div>
            
            @if($role !=3 && $role !=4)
            <div class="layui-form-item">
                <label for="spend" class="layui-form-label">商务费用金额</label>
                <div class="layui-input-inline">
                    <input type="text" id="spend" name="spend" autocomplete="off" class="layui-input power" value="{{ $list->spend }}" @if($role == 5) disabled @endif>
                </div> 
            </div>
            @endif
            
            <div class="layui-form-item layui-form-text">
                <label for="comments" class="layui-form-label">备注</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="comments" name="comments" class="layui-textarea power" @if($role == 5) disabled @endif>{{ $list->remarks }}</textarea>
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
                    <select id="process" name="process">
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

            @if($role != 5)
            <div class="layui-form-item">
                <label for="user_id" class="layui-form-label">流转</label>
                <div class="layui-input-inline">
                    <div id="user_id" class="xm-select-demo"></div>
                </div>
            </div>
            @endif
            
            @if($role == 1 || $role == 2)
            @if($contract)
                <div class="layui-form-item">
                    <label for="title" class="layui-form-label"><span class="x-red">*</span>合同标题</label>
                    <div class="layui-input-inline">
                        <input type="text" id="title" name="title" autocomplete="off" class="layui-input power" value="{{ $contract->title }}">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="number" class="layui-form-label">合同编号</label>
                    <div class="layui-input-inline">
                        <input type="text" id="number" name="number" autocomplete="off" class="layui-input power" value="{{ $contract->number }}">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="starttime" class="layui-form-label">开始时间</label>
                    <div class="layui-input-inline">
                        <input type="text" id="starttime" name="starttime" autocomplete="off" class="layui-input power" value="{{ $contract->starttime ? date('Y-m-d',$contract->starttime) : '' }}">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="endtime" class="layui-form-label">截止时间</label>
                    <div class="layui-input-inline">
                        <input type="text" id="endtime" name="endtime" autocomplete="off" class="layui-input power" value="{{ $contract->endtime ? date('Y-m-d',$contract->endtime) : '' }}">
                    </div>
                </div>
                
                @if($imageinfo)
                    @foreach($imageinfo as $key=>$value)
                        <div class="layui-form-item" id="picture{{$key}}">
                            <label for="picture" class="layui-form-label">图片{{ $key == 0 ? '' : $key }}</label>
                            <div class="layui-input-inline">
                                <input type="file" name="picture{{ $value->id }}" autocomplete="off" class="layui-input power">
                            </div>
                            <div class="layui-form-mid layui-word-aux">
                                @if($imageinfo && $value['link'])
                                    <a href="{{ asset($value->link) }}" target="_blank" style="float: left;"><img src="{{ asset($value->link) }}"  width="100" height="100"></a>
                                @endif
                                <input type="hidden" id="imageid{{ $key }}" value="{{ $value->id }}" /> 
                                
                                @if($key > 0)
                                  <a href="javascript:void(0);" title="删除" onclick="del_picture('{{ $key }}','{{ route('admin.orders.delimage',['id'=>$value->id]) }}')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>
                                @else
                                  <a href="javascript:void(0);" title="添加图片" onclick="add_picture()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif

                <div class="layui-form-item" id="addpicture" style="display: none;"></div>

                @if($annexinfo)
                    @foreach($annexinfo as $key=>$value)
                        <div class="layui-form-item" id="annex{{$key}}">
                            <label for="annex" class="layui-form-label">附件{{ $key == 0 ? '' : $key }}</label>
                            <div class="layui-input-inline">
                                <input type="file" name="annex{{ $value->id }}" autocomplete="off" class="layui-input power">
                            </div>
                            <div class="layui-form-mid layui-word-aux">
                                @if($annexinfo && $value['link'])
                                    <a href="{{ asset($value->link) }}" target="_blank" style="float: left;"><img src="{{ asset('images/wenjian.jpg') }}"  width="100" height="100" title="{{ $value->link }}"></a>
                                @endif
                                <input type="hidden" id="annexid{{ $key }}" value="{{ $value->id }}" /> 
                                
                                @if($key > 0)
                                  <a href="javascript:void(0);" title="删除" onclick="del_annex('{{ $key }}','{{ route('admin.orders.delimage',['id'=>$value->id]) }}')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>
                                @else
                                  <a href="javascript:void(0);" title="添加附件" onclick="add_annex()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif

                <div id="addannex" style="display: none;"></div>
                        
                <div class="layui-form-item layui-form-text">
                    <label for="notes" class="layui-form-label">合同备注</label>
                    <div class="layui-input-block">
                        <textarea placeholder="请输入内容" id="notes" name="notes" class="layui-textarea power">{{ $contract->remark }}</textarea>
                    </div>
                </div>
                <input type="hidden" name="con_id" value="1">
            @endif
            @endif
            <div class="layui-form-item">
                <input type="button" class="layui-btn" value="提交" onclick="editContract();">
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

    @if(session('code') == '0')
        parent.window.location.reload();
    @elseif(session('code') == '1')
        layer.msg("{{ session('msg') }}");
        return false;
    @endif

    @if($role == 3 || $role == 4)
        $('.power').attr('disabled',true);
    @endif
    form.render();

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
 
    @if(isset($stagesinfo[1]) && $stagesinfo[1])
        @foreach($stagesinfo[1] as $v)
        //执行一个laydate实例
        laydate.render({
            elem: '#billing_time{{ $loop->iteration }}',
            type: 'datetime',
            trigger: 'click',
            value: '{{ $v }}'
        });
        @endforeach
    @else
        @foreach($stagesinfo[0] as $val)
        //执行一个laydate实例
        laydate.render({
            elem: '#billing_time{{ $loop->iteration }}',
            type: 'datetime',
            trigger: 'click'
        });
        @endforeach
    @endif
    @if(isset($stagesinfo[2]) && $stagesinfo[2])
        @foreach($stagesinfo[2] as $vv)
        //执行一个laydate实例
        laydate.render({
            elem: '#collection_time{{ $loop->iteration }}',
            type: 'datetime',
            trigger: 'click',
            value: '{{ $vv }}'
        });
        @endforeach
    @else
        @foreach($stagesinfo[0] as $val)
        //执行一个laydate实例
        laydate.render({
            elem: '#collection_time{{ $loop->iteration }}',
            type: 'datetime',
            trigger: 'click'
        });
        @endforeach
    @endif

    //业务种类选择
    form.on("select(goods)", function(data){
        $.ajax({
            type: "GET",
            url: "{{ route('admin.orders.getcity') }}",
            headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            data: { good: $(data.elem).find("option:selected").text()},
            dataType: "json",
            success: function(result){
                //省
                var p = $(data.elem).parent().siblings().find(".xm-select-demo").eq(0).attr("id");
                //市
                $(data.elem).parent().siblings().find(".xm-select-demo").eq(1).empty();
                
                var xmList = xmSelect.get('#'+p);

                xmList[0].setValue([ ]);
                xmList[0].update({
                    template({ item, sels, name, value }){
                        if(result.isReviewGoods == 0){
                            return item.name;
                        }
                        else if($(data.elem).find("option:selected").text() == 'ISP'){
                            return item.name  + '<input type="checkbox" lay-skin="primary" title="评测" value="'+data.value+'|'+value+'" name="review[]"/>'  + '<input type="checkbox" lay-skin="primary" title="含网" value="'+value+'" name="network[]"/>'; 
                        }else{
                            return item.name  + '<input type="checkbox" lay-skin="primary" title="评测" value="'+data.value+'|'+value+'" name="review[]"/>';  
                        }
                        
                    }
                });
                form.render();
            }
        });
    });

    @foreach($goodProvince as $key=>$value)
    createProvinceXm(form, "#province{{ $loop->iteration }}", @json($value['province']), "#city{{ $loop->iteration }}");

    @if(isset($value['city']))
    createCityXm(form, "#city{{ $loop->iteration }}", @json($value['city']));
    @endif

    @endforeach
});

function editContract(){
    if($("#customer option:selected").val() == '')
    {
        layer.msg('请选择客户名称');
        return false;
    }

    var go = true;
    var goods = $('.goods option:selected').each(function (){
        if($(this).val() == '' || $(this).val() == '0')
        {
            layer.msg('请选择业务种类');
            go = false;
            return false;
        }
    });
    if(go === false){
        return false;
    }

    var ci = true;
    var pro = xmSelect.get(/#province.*/);
    for(var i=0;i<pro.length;i++){
        a = pro[i].getValue('valueStr');
        if(a == ''){
            layer.msg('请选择省或市');
            ci = false;
            return false;
        }
    }
    if(ci === false){
        return false;
    }
    var role = "{{$role}}";
    if(role !=3 && role !=4){
    if(!/^[1-9]\d*$/.test($('#plannedamt').val()))
    {
        layer.msg('请填写正确的合同金额');
        return false;
    }

    var sum = 0;
    var rs = true;
    var data = $('.stages').each(function (){

        if(!/^[1-9]\d*$/.test($(this).val()))
        {
            layer.msg('请填写正确的分期金额');
            rs = false;
            return false;
        }
        sum += parseInt($(this).val());
    });
    if(rs === false){
        return false;
    }
    if(sum != $('#plannedamt').val())
    {
        layer.msg('分期总金额与合同金额不符');
        return false;
    }
    }
    if($('#title').is(':visible') && $('#title').val() == '')
    {
        layer.msg('请填写合同标题');
        return false;
    }
    
    $("#form").submit();
};

var image_num = "{{ count($imageinfo) }}";
var annex_num = "{{ count($annexinfo) }}";
var stages_num = "{{ count($stagesinfo[0]) }}";
function add_picture()
{
    image_num ++;
    $('#addpicture').before('<div class="layui-form-item" id="picture' + image_num +'">'
        +'<label for="picture' + image_num + '" class="layui-form-label">图片' + image_num +'</label>'
        +'<div class="layui-input-inline">'
        +'<input type="file" name="picture' + image_num + '" autocomplete="off" class="layui-input">'
        +'</div>'
        +'<div class="layui-form-mid layui-word-aux">'
        +'<a href="javascript:void(0);" title="删除" onclick="del_picture(' + image_num + ')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>'
        +'</div>'
        +'</div>'
    );
}

function del_picture(imagenum,uri)
{
    var imagenum = imagenum;
    var imageid = $('#imageid' + imagenum).val();
    if (imageid)
    {
        layer.confirm('删除的数据不可恢复，您确定要删除该条图片信息吗？', function(index){
            $.ajax({
                type: "GET",
                url: uri,
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                success: function(result){
                    if(result.code > 0){
                      layer.msg(result.msg);
                      return false;
                    }
                    else{
                      location.reload();
                    }
                }
            });
            
        });
        return false;
    }
    $('#picture' + imagenum).remove();
} 

function add_annex()
{
    annex_num ++;
    $('#addannex').before('<div class="layui-form-item" id="annex' + annex_num +'">'
        +'<label for="annex' + annex_num + '" class="layui-form-label">附件' + annex_num +'</label>'
        +'<div class="layui-input-inline">'
        +'<input type="file" name="annex' + annex_num + '" autocomplete="off" class="layui-input">'
        +'</div>'
        +'<div class="layui-form-mid layui-word-aux">'
        +'<a href="javascript:void(0);" title="删除" onclick="del_annex(' + annex_num + ')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>'
        +'</div>'
        +'</div>'
    );
}

function del_annex(annexnum,uri)
{
    var annexnum = annexnum;
    var annexid = $('#annexid' + annexnum).val();
    if (annexid)
    {
        layer.confirm('删除的数据不可恢复，您确定要删除该条附件信息吗？', function(index){
            $.ajax({
                type: "GET",
                url: uri,
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                success: function(result){
                    if(result.code > 0){
                      layer.msg(result.msg);
                      return false;
                    }
                    else{
                      location.reload();
                    }
                }
            });
            
        });
        return false;
    }
    $('#annex' + annexnum).remove();
} 

function add_stages()
{
    stages_num ++;
    $('#addstages').before('<div class="layui-form-item" id="stages' + stages_num +'">'
        +'<label for="stages" class="layui-form-label"><span class="x-red">*</span>第'+ stages_num +'期金额</label>'
        +'<div class="layui-input-inline">'
        +'<input type="text" name="stages[]" autocomplete="off" class="layui-input stages" value="">'
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
    var stagesid = $('#stagesid' + stagesnum).val();
    if (stagesid)
    {
        $('#stages' + stagesnum).remove();
    }
    $('#stages' + stagesnum).remove();
} 

var goods_num = "{{ count($goodProvince) }}";
function add_goods()
{
    layui.use(['form','layer','laydate'], function(){
        var form = layui.form;
        goods_num ++;
        $('#addgoods').before('<div class="layui-form-item" id="goods' + goods_num +'">'
            +'<label for="goods" class="layui-form-label"><span class="x-red">*</span>业务种类</label>'
            +'<div class="layui-input-inline">'
            +'<select name="goods[]" class="goods" lay-filter="goods">'
            +'<option value="0"></option>'
            +'@foreach($good as $key=>$val)'
            +'<option value="{{ $key }}">{{ $val }}</option>'
            +'@endforeach'
            +'</select>'
            +'</div>'
            +'<div class="layui-input-inline">'
            +'<div id="province' + goods_num +'" class="xm-select-demo"></div>'
            +'</div>'
            +'<div class="layui-input-inline">'
            +'<div id="city' + goods_num +'" class="xm-select-demo"></div>'
            +'</div>'
            +'<div class="layui-form-mid layui-word-aux">'
            +'<a href="javascript:void(0);" title="删除业务种类" onclick="del_goods(' + goods_num + ')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>'
            +'</div>'
            +'</div>'
        );
        createProvinceXm(form, "#province"+ goods_num, @json($prov), "#city"+ goods_num);
    });
}
                 
function del_goods(goodsnum)
{
    var goodsnum = goodsnum;
    $('#goods' + goodsnum).remove();
}

function createProvinceXm(layform, provinceId, provinceData, cityId){
    xmSelect.render({
        el: provinceId,
        style: {
            width: "188px"
        },
        name: "province[]",
        filterable: true,
        layVerify: "required",
        layVerType: "tips",

        @if(!in_array($role, [1, 2]))
        disabled: true,
        @endif

        template({ item, sels, name, value }){
            var ischecked;
            var ischeckeds;
            if(item.review == 1){
                ischecked = 'checked';
            }
            if(item.network == 1){
                ischeckeds = 'checked';
            }
            var reviewValue = $(provinceId).parent().siblings().find(".goods option:selected").val();
            if($(provinceId).parent().siblings().find(".goods option:selected").text() == 'ISP'){
                return item.name  + '<input type="checkbox" lay-skin="primary" title="评测" value="'+reviewValue+'|'+value+'" name="review[]" '+ischecked+'>'  + '<input type="checkbox" lay-skin="primary" title="含网" value="'+value+'" name="network[]" '+ischeckeds+'>';
            }else{
                return item.name  + '<input type="checkbox" lay-skin="primary" title="评测" value="'+reviewValue+'|'+value+'" name="review[]" '+ischecked+'>';
            }
        },
        data: provinceData,
        filterDone: function(val, list){
            layform.render();
        },
        on: function(data){
            var good = $(provinceId).parent().siblings().find(".goods option:selected").text();
            //arr:  当前多选已选中的数据
            var arr = data.arr;
            var uri = "{{ route('admin.orders.getcity') }}";
            $.ajax({
                type: "GET",
                url: uri,
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                data: { code:arr, good:good },
                success: function(json){
                    createCityXm(layform, cityId, json);
                    layform.render();
                }
            });
        },
        show: function(){
            layform.render();
        }
    });
    layform.render();
}
function createCityXm(layform, cityId, cityData){
    var data = cityData;
    if(cityData.city != undefined){
        data = cityData.city;
    }
    xmSelect.render({
        el: cityId,
        style: {
            width: "188px"
        },
        name: "city[]",
        filterable: true,
        layVerify: "required",
        layVerType: "tips",
        
        @if(!in_array($role, [1, 2]))
        disabled: true,
        @endif

        template({ item, sels, name, value }){
            if(cityData.isReviewGoods == 0){
                return item.name;
            }
            else{
                var ischecked;
                if(item.review == 1){
                    ischecked = 'checked';
                }
                var reviewValue = $(cityId).parent().siblings().find(".goods option:selected").val();
                return item.name  + '<input type="checkbox" lay-skin="primary" title="评测" value="'+reviewValue+'|'+value+'" name="review[]" '+ischecked+'/>'; 
            }
        },
        data: data,
        filterDone: function(val, list){
            layform.render();
        },
        show: function(){
            layform.render();
        }
    });
    
    if(cityData.isCityGoods == 0 || data.length == 0){
        $(cityId).css("display", "none");
    }
    else if(cityData.isCityGoods == 1){
        $(cityId).css("display", "");
    }
    layform.render();
}
@if($role != 5)
xmSelect.render({
    el: "#user_id",
    style: {
        width: "188px"
    },
    name: "user_id",
    layVerify: "required",
    layVerType: "tips",
    data: @json($username)
})
@endif
</script>
@stop