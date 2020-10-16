@extends('admin.layout')

@section('title', '客户详情')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
       <div class="layui-col-md12">
          <div class="layui-card">
             <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
              <ul class="layui-tab-title">
                <li class="layui-this"><a href="{{ route('admin.customer.show',$id) }}">客户详情</a></li> 
                <li><a href="{{ route('admin.bank.index',['id'=>$id]) }}">客户银行卡</a></li>
                <li><a href="{{ route('admin.fup.index',['id'=>$id]) }}">跟进记录</a></li>
                <li><a href="{{ route('admin.customer.record',['id'=>$id]) }}">客户领取分配记录</a></li>
              </ul> 
             </div>  
          </div>
          <div class="layui-row layui-form-pane">

        <div class="layui-form-item">
            <label class="layui-form-label">公司名称:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{$data->company}}" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">公司地址:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{$data->address}}" disabled>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">公司座机:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{$data->tel}}" disabled>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">用户名:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{$data->username}}" disabled>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">密码:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{$data->password}}" disabled>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">联系人:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{$data->name}}" disabled>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">联系人电话:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{$data->phone}}" disabled>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">email:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{$data->emil}}" disabled>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">职位:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{$data->job}}" disabled>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">添加时间:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{$data->created_at}}" disabled>
            </div>
        </div> 
        <div class="layui-form-item">
            <label class="layui-form-label">修改时间:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{$data->updated_at}}" disabled>
            </div>
        </div>  
        
        <div class="layui-form-item">
            <label class="layui-form-label">客户来源:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="{{!empty($source) ? $source->name : ''}}" disabled>
            </div>
        </div> 
        <div class="layui-form-item">
            <label class="layui-form-label">公海状态:</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" value="@if($data->status ==0) 未分配 @else 已分配 @endif" disabled>
            </div>
        </div>   
        <div class="layui-form-item">
            <label class="layui-form-label">客户标签:</label>
            <div class="layui-input">
            @foreach($tags as $arr) 
            <span class="layui-badge @if(strlen($arr->tagname) <= 9) layui-bg-orange @elseif(strlen($arr->tagname) > 9 && strlen($arr->tagname) <=15) layui-bg-green @elseif(strlen($arr->tagname) > 15 && strlen($arr->tagname) <=24) layui-bg-blue @elseif(strlen($arr->tagname) > 24 && strlen($arr->tagname) <=36) layui-bg-black @else   @endif">{{$arr->tagname}}</span>  
            @endforeach
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">备注:</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" disabled>{{$data->remarks}}</textarea>
            </div>
        </div>

    </div>
    </div>
    </div>
</div>
@stop