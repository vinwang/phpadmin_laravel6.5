@extends('admin.layout')

@section('title', '详情')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row layui-form-pane">

        <div class="layui-form-item">
            <label class="layui-form-label">订单编号:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->order->order_num }}" disabled>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">客户名称:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->customer->company }}" disabled>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">@if($list->type == 1)资质@else合同@endif标题:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->title }}" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">@if($list->type == 1)资质@else合同@endif编号:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->number }}" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">开始时间:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->starttime ? date('Y-m-d',$list->starttime) : '' }}" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">截止时间:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{ $list->endtime ? date('Y-m-d',$list->endtime) : '' }}" disabled>
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">@if($list->type == 1)资质@else合同@endif备注:</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" disabled>{{ $list->remark }}</textarea>
            </div>
        </div> 
        @if($picture->first() != null)
            <div class="layui-form-item">
                <label class="layui-form-label">图片:</label>
                <div>
                    @foreach($picture as $pic)
                        <a href="{{ asset($pic->link) }}" target="_blank" style="float: left;"><img src="{{ asset($pic->link) }}"  width="100" height="100"></a>
                    @endforeach
                </div>
            </div>
        @endif
        @if($annex->first() != null)
            <div class="layui-form-item">
                <label class="layui-form-label">附件:</label>
                <div>
                    @foreach($annex as $nex)
                        <a href="{{ asset($nex->link) }}" target="_blank" style="float: left;"><img src="{{ asset('images/wenjian.jpg') }}"  width="100" height="100" title="{{ $nex->link }}"></a>
                    @endforeach
                </div>
            </div>
        @endif
        @if($qualified->first() != null)
            <div class="layui-form-item">
                <label class="layui-form-label">资质证书:</label>
                <div>
                    @foreach($qualified as $qua)
                        <a href="{{ asset($qua->link) }}" target="_blank" style="float: left;"><img src="{{ asset($qua->link) }}"  width="100" height="100" title="{{ $qua->link }}"></a>
                    @endforeach
                </div>
            </div>
        @endif
        
    </div>
</div>
@stop