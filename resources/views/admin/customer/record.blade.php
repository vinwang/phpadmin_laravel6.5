@extends('admin.layout')

@section('title', '客户领取分配记录')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-tab layui-tab-brief">
                    <ul class="layui-tab-title">
                        <li><a href="{{ route('admin.customer.show',$id) }}">客户详情</a></li> 
                        <li><a href="{{ route('admin.bank.index',['id'=>$id]) }}">客户银行卡</a></li>
                        <li><a href="{{ route('admin.fup.index',['id'=>$id]) }}">跟进记录</a></li>
                        <li class="layui-this"><a href="{{ route('admin.customer.record',['id'=>$id]) }}">客户领取分配记录</a></li>
                    </ul> 
                </div>  
            </div>
            <div class="layui-row layui-form-pane">
                @foreach($lists as $list)
                    <ul class="layui-timeline">
                        <li class="layui-timeline-item">
                            <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                            <div class="layui-timeline-content layui-text"><hr>
                                <h3 class="layui-timeline-title">{{ $list->created_at }}</h3>
                                <p>
                                  客户名称：{{ $customer[$list->customer_id] }}<br>
                                  @if($list->type == 0)新增客户所属人@elseif($list->type == 1)原客户所属人@elseif($list->type == 2)被分配客户所属人@elseif($list->type == 3)被退回客户所属人@endif：{{ $user[$list->person_id] }}<br>
                                  @if($list->type == 0)操作人@elseif($list->type == 1)领取人@elseif($list->type == 2)分配人@elseif($list->type == 3)退回人@endif： {{ $list->user_id ? $user[$list->user_id] : '系统自动退回至公海'}}
                                </p>
                            </div>
                        </li>
                    </ul>
                @endforeach
            </div>
        </div>
    </div>
</div>
@stop