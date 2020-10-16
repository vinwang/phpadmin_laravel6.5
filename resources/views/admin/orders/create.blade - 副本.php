@extends('admin.layout')

@section('title', '添加订单')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form layui-form-pane" method="POST" action="{{ route('admin.orders.store') }}" enctype="multipart/form-data" id="form">
            {{ csrf_field() }}

            <div class="layui-form-item">
                <label for="customer" class="layui-form-label"><span class="x-red">*</span>客户名称</label>
                <div class="layui-input-inline">
                    <select id="customer" name="customer" lay-search>
                        <option value=""></option>
                        @foreach($customer as $key=>$val)
                            <option value="{{ $key }}" {{ (old('customer') == $key ? 'selected':'') }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div> 
            @if(old('goods'))
                @foreach( old('goods') as $value)
                    <div class="layui-form-item">
                        <label for="goods" class="layui-form-label"><span class="x-red">*</span>业务种类</label>
                        <div class="layui-input-inline">
                            <select name="goods[]" class="goods">
                                <option value=""></option>
                                @foreach($good as $key=>$val)
                                    <option value="{{ $key }}" {{ $value == $key ? 'selected' : '' }}>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="layui-input-inline">
                            <div id="province" class="xm-select-demo"></div>
                        </div>
                        <div class="layui-input-inline">
                            <div id="city" class="xm-select-demo"></div>
                        </div>
                        <div class="layui-form-mid layui-word-aux">
                            @if($loop->iteration == 1)
                                <a href="javascript:void(0);" title="添加业务种类" onclick="add_goods()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                            @else
                                <a href="javascript:void(0);" title="删除业务种类" onclick="del_goods('{{ $loop->iteration }}')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>
                            @endif
                        </div>
                    </div> 
                @endforeach
            @else
                <div class="layui-form-item">
                    <label for="goods" class="layui-form-label"><span class="x-red">*</span>业务种类</label>
                    <div class="layui-input-inline">
                        <select name="goods[]" class="goods" lay-filter="goods">
                            <option value=""></option>
                            @foreach($good as $key=>$val)
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="layui-input-inline">
                        <div id="province" class="xm-select-demo"></div>
                    </div>
                    <div class="layui-input-inline">
                        <div id="city" class="xm-select-demo"></div>
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <a href="javascript:void(0);" title="添加业务种类" onclick="add_goods()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                    </div>
                </div>  
            @endif
             
            <div id="addgoods" style="display: none;"></div>
            
            <div class="layui-form-item">
                <label for="plannedamt" class="layui-form-label"><span class="x-red">*</span>合同金额</label>
                <div class="layui-input-inline">
                    <input type="text" id="plannedamt" name="plannedamt" autocomplete="off" class="layui-input" value="{{ old('plannedamt') }}">
                </div>
            </div>
            
            @if(old('stages'))
                @foreach(old('stages') as $value)
                    <div class="layui-form-item">
                        <label for="stages" class="layui-form-label"><span class="x-red">*</span>第{{ $loop->iteration }}期金额</label>
                        <div class="layui-input-inline">
                            <input type="text" name="stages[]" autocomplete="off" class="layui-input stages" value="{{ $value }}">
                        </div>
                        <div class="layui-form-mid layui-word-aux">
                            @if($loop->iteration == 1)
                                <a href="javascript:void(0);" title="添加分期" onclick="add_stages()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                            @else
                                <a href="javascript:void(0);" title="删除分期" onclick="del_stages('{{ $loop->iteration }}')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="layui-form-item">
                    <label for="stages" class="layui-form-label"><span class="x-red">*</span>第1期金额</label>
                    <div class="layui-input-inline">
                        <input type="text" name="stages[]" autocomplete="off" class="layui-input stages" value="">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <a href="javascript:void(0);" title="添加分期" onclick="add_stages()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                    </div>
                </div>
            @endif 

            <div id="addstages" style="display: none;"></div>

            <div class="layui-form-item">
                <label for="spend" class="layui-form-label">商务费用金额</label>
                <div class="layui-input-inline">
                    <input type="text" id="spend" name="spend" autocomplete="off" class="layui-input" value="{{ old('spend') }}">
                </div>
            </div>
               
            <div class="layui-form-item layui-form-text">
                <label for="remarks" class="layui-form-label">备注</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="remarks" name="remarks" class="layui-textarea">{{ old('remarks') }}</textarea>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="user_id" class="layui-form-label">流转</label>
                <div class="layui-input-inline">
                    <div id="user_id" class="xm-select-demo"></div>
                </div>
            </div>

            <div class="layui-form-item display" style="display: none;">
                <label for="title" class="layui-form-label"><span class="x-red">*</span>合同标题</label>
                <div class="layui-input-inline">
                    <input type="text" id="title" name="title" autocomplete="off" class="layui-input submit" disabled value="{{ old('title') }}">
                </div>
            </div>

            <div class="layui-form-item display" style="display: none;">
                <label for="number" class="layui-form-label">合同编号</label>
                <div class="layui-input-inline">
                    <input type="text" id="number" name="number" autocomplete="off" class="layui-input submit" disabled value="{{ old('number') }}">
                </div>
            </div>

            <div class="layui-form-item display" style="display: none;">
                <label for="starttime" class="layui-form-label">开始时间</label>
                <div class="layui-input-inline">
                    <input type="text" id="starttime" name="starttime" autocomplete="off" class="layui-input submit" disabled value="{{ old('starttime') }}">
                </div>
            </div>

            <div class="layui-form-item display" style="display: none;">
                <label for="endtime" class="layui-form-label">截止时间</label>
                <div class="layui-input-inline">
                    <input type="text" id="endtime" name="endtime" autocomplete="off" class="layui-input submit" disabled value="{{ old('endtime') }}">
                </div>
            </div>

            <div class="layui-form-item display" style="display: none;">
                <label for="picture" class="layui-form-label">图片</label>
                <div class="layui-input-inline">
                    <input type="file" name="picture[]" autocomplete="off" class="layui-input submit" disabled>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <a href="javascript:void(0);" title="添加图片" onclick="add_picture()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                </div>
            </div>

            <div class="layui-form-item" id="addpicture" style="display: none;"></div>

            <div class="layui-form-item display" style="display: none;">
                <label for="annex" class="layui-form-label">附件</label>
                <div class="layui-input-inline">
                    <input type="file" name="annex[]" autocomplete="off" class="layui-input submit" disabled>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <a href="javascript:void(0);" title="添加附件" onclick="add_annex()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                </div>
            </div>

            <div id="addannex" style="display: none;"></div>
                    
            <div class="layui-form-item layui-form-text display" style="display: none;">
                <label for="notes" class="layui-form-label">合同备注</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="notes" name="notes" class="layui-textarea submit" disabled>{{ old('notes') }}</textarea>
                </div>
                <input type="hidden" name="con_id" value="1" class="submit" disabled>
            </div>

            <div class="layui-col-md12">
                <a href="javascript:;" onclick="showContract();" class="under"><i class="layui-icon">&#xe654;</i>添加合同</a>
                <a href="javascript:;" onclick="hideContract();" class="undershow" style="display: none;float: right;">删除合同</a>
                <input type="button" class="layui-btn float" value="提交" style="float: right;" onclick="addContract();">
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
    var layer = layui.layer;
    @if(session('code') == '0')
        parent.window.location.reload();
    @elseif(session('code') == '1')
        layer.msg("{{ session('msg') }}");
        return false;
    @endif

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

    createProvinceXm(form, "#province", @json($province), "#city");
});

function showContract(){
    $('.display').show();
    $('.under').hide();
    $('.undershow').show();
    $('.float').removeAttr('style');
    $('.submit').removeAttr('disabled');
}

function hideContract(){
    $('.undershow').hide();
    $('.display').hide();
    $('.under').show();
    $('.float').css('float','right');
    $('.submit').attr('disabled','disabled');
}
var image_num = 0;
var annex_num = 0;
var stages_num = 1;
var goods_num = 0;
function add_picture()
{
    image_num ++;
    $('#addpicture').before('<div class="layui-form-item" id="picture' + image_num +'">'
        +'<label for="picture' + image_num + '" class="layui-form-label">图片</label>'
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
        +'<label for="annex" class="layui-form-label">附件</label>'
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

        createProvinceXm(form, "#province"+ goods_num, @json($province), "#city"+ goods_num);
    });
}
              
function del_goods(goodsnum)
{
    var goodsnum = goodsnum;
    $('#goods' + goodsnum).remove();
}

function addContract(){
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

    if($('#spend').val()){
        if(!/^[1-9]\d*$/.test($('#spend').val()))
        {
            layer.msg('请填写正确的商务费用金额');
            return false;
        }
    }

    if($('#title').is(':visible') && $('#title').val() == '')
    {
        layer.msg('请填写合同标题');
        return false;
    }
    $("#form").submit();
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
        template({ item, sels, name, value }){
            var reviewValue = $(provinceId).parent().siblings().find(".goods option:selected").val();
            return item.name  + '<input type="checkbox" lay-skin="primary" title="评测" value="'+reviewValue+'|'+value+'" name="review[]"/>'
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
    xmSelect.render({
        el: cityId,
        style: {
            width: "188px"
        },
        name: "city[]",
        filterable: true,
        layVerify: "required",
        layVerType: "tips",
        template({ item, sels, name, value }){
            if(cityData.isReviewGoods == 0){
                return item.name;
            }
            else{
                var reviewValue = $(cityId).parent().siblings().find(".goods option:selected").val();
                return item.name  + '<input type="checkbox" lay-skin="primary" title="评测" value="'+reviewValue+'|'+value+'" name="review[]"/>';  
            }
        },
        data: cityData.city,
        filterDone: function(val, list){
            layform.render();
        },
        show: function(){
            layform.render();
        }
    });
    
    if(cityData.isCityGoods == 0 || cityData.city.length == 0){
        $(cityId).css("display", "none");
    }
    else if(cityData.isCityGoods == 1){
        $(cityId).css("display", "");
    }
    layform.render();
}

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
</script>
@stop