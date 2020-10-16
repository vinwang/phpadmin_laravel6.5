@extends('admin.layout')

@section('title', '编辑合同')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form layui-form-pane" method="POST" action="{{ route('admin.contract.update',$list->id) }}" enctype="multipart/form-data" id="form">
            {{ csrf_field() }}
            @method('PUT')
            <div class="layui-form-item">
                <label for="customer" class="layui-form-label"><span class="x-red">*</span>客户名称</label>
                <div class="layui-input-inline">
                    <input type="text" id="customer" name="customer" autocomplete="off" class="layui-input" value="{{ $list->customer->company }}" readonly>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="order" class="layui-form-label"><span class="x-red">*</span>对应销售订单</label>
                <div class="layui-input-inline">
                    <div id="order" class="xm-select-demo"></div>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="title" class="layui-form-label"><span class="x-red">*</span>合同标题</label>
                <div class="layui-input-inline">
                    <input type="text" id="title" name="title" datatype="*" autocomplete="off" class="layui-input" value="{{ $list->title }}">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="number" class="layui-form-label">合同编号</label>
                <div class="layui-input-inline">
                    <input type="text" id="number" name="number" autocomplete="off" class="layui-input" value="{{ $list->number }}">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="starttime" class="layui-form-label">开始时间</label>
                <div class="layui-input-inline">
                    <input type="text" id="starttime" name="starttime" autocomplete="off" class="layui-input" value="{{ $list->starttime ? date('Y-m-d',$list->starttime) : '' }}">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="endtime" class="layui-form-label">截止时间</label>
                <div class="layui-input-inline">
                    <input type="text" id="endtime" name="endtime" autocomplete="off" class="layui-input" value="{{ $list->endtime ? date('Y-m-d',$list->endtime) : '' }}">
                </div>
            </div>
            @if($qualifiedinfo->count() && $list->type == 1)
                @foreach($qualifiedinfo as $key=>$value)
                    <div class="layui-form-item" id="qualified{{$key}}">
                        <label for="picture" class="layui-form-label">上传资质</label>
                        <div class="layui-input-inline">
                            <input type="file" name="qualified{{ $value->id }}" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-mid layui-word-aux">
                            @if($qualifiedinfo && $value['link'])
                                <a href="{{ asset($value->link) }}" target="_blank" style="float: left;"><img src="{{ asset($value->link) }}"  width="100" height="100"></a>
                            @endif
                            <input type="hidden" id="qualifiedid{{ $key }}" value="{{ $value->id }}" /> 
                            
                            @if($key > 0)
                              <a href="javascript:void(0);" title="删除" onclick="del_qualified('{{ $key }}','{{ route('admin.orders.delimage',['id'=>$value->id]) }}')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>
                            @else
                              <a href="javascript:void(0);" title="添加资质" onclick="add_qualified()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                            @endif
                        </div>
                    </div>
                @endforeach
            @elseif($list->type == 1)
                <div class="layui-form-item">
                    <label for="qualified" class="layui-form-label">上传资质</label>
                    <div class="layui-input-inline">
                        <input type="file" name="qualified0" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <a href="javascript:void(0);" title="添加资质" onclick="add_qualified()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                    </div>
                </div>
            @endif

            <div class="layui-form-item" id="addqualified" style="display: none;"></div>

            @if($imageinfo->count())
                @foreach($imageinfo as $key=>$value)
                    <div class="layui-form-item" id="picture{{$key}}">
                        <label for="picture" class="layui-form-label">图片</label>
                        <div class="layui-input-inline">
                            <input type="file" name="picture{{ $value->id }}" autocomplete="off" class="layui-input">
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
            @elseif($list->type == 0)
                <div class="layui-form-item">
                    <label for="picture" class="layui-form-label">图片</label>
                    <div class="layui-input-inline">
                        <input type="file" name="picture0" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <a href="javascript:void(0);" title="添加图片" onclick="add_picture()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                    </div>
                </div>
            @endif

            <div class="layui-form-item" id="addpicture" style="display: none;"></div>
            @if($annexinfo->count())
                @foreach($annexinfo as $key=>$value)
                    <div class="layui-form-item" id="annex{{$key}}">
                        <label for="annex" class="layui-form-label">附件</label>
                        <div class="layui-input-inline">
                            <input type="file" name="annex{{ $value->id }}" autocomplete="off" class="layui-input">
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
            @elseif($list->type == 0)
                <div class="layui-form-item">
                    <label for="annex" class="layui-form-label">附件</label>
                    <div class="layui-input-inline">
                        <input type="file" name="annex0" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <a href="javascript:void(0);" title="添加附件" onclick="add_annex()"><i class="layui-icon layui-bg-green">&#xe654;</i></a>
                    </div>
                </div>
            @endif

            <div id="addannex" style="display: none;"></div>
                    
            <div class="layui-form-item layui-form-text">
                <label for="remark" class="layui-form-label">合同备注</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="remark" name="remark" class="layui-textarea">{{ $list->remark }}</textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <input type="hidden" name="type" value="{{ $list->type }}">
                <input type="button" class="layui-btn" value="编辑" onclick="editContract();">
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
    data: @json($order)
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

function editContract(){
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


var image_num = "{{ count($imageinfo) }}";
var annex_num = "{{ count($annexinfo) }}";
var qualified_num = "{{ count($qualifiedinfo) }}";
function add_picture()
{
    image_num ++;
    $('#addpicture').before('<div class="layui-form-item" id="picture' + image_num +'">'
        +'<label for="picture' + image_num + '" class="layui-form-label">图片</label>'
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

function add_qualified()
{
    qualified_num ++;
    $('#addqualified').before('<div class="layui-form-item" id="qualified' + qualified_num +'">'
        +'<label for="picture' + qualified_num + '" class="layui-form-label">上传资质</label>'
        +'<div class="layui-input-inline">'
        +'<input type="file" name="qualified' + qualified_num + '" autocomplete="off" class="layui-input">'
        +'</div>'
        +'<div class="layui-form-mid layui-word-aux">'
        +'<a href="javascript:void(0);" title="删除" onclick="del_qualified(' + qualified_num + ')"><i class="layui-icon layui-bg-red">&#x1006;</i></a>'
        +'</div>'
        +'</div>'
    );
}

function del_qualified(imagenum,uri)
{
    var imagenum = imagenum;
    var imageid = $('#qualifiedid' + imagenum).val();
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
    $('#qualified' + imagenum).remove();
} 

function add_annex()
{
    annex_num ++;
    $('#addannex').before('<div class="layui-form-item" id="annex' + annex_num +'">'
        +'<label for="annex' + annex_num + '" class="layui-form-label">附件</label>'
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
</script>
@stop