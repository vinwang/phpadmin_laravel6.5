@extends('admin.layout')

@section('title', '跟进记录')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
        <div class="layui-card">
             <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
              <ul class="layui-tab-title">
                <li><a href="{{ route('admin.customer.show',$id) }}">客户详情</a></li> 
                <li><a href="{{ route('admin.bank.index',['id'=>$id]) }}">客户银行卡</a></li>
                <li class="layui-this"><a href="{{ route('admin.fup.index',['id'=>$id]) }}">跟进记录</a></li>
                <li><a href="{{ route('admin.customer.record',['id'=>$id]) }}">客户领取分配记录</a></li>
              </ul> 
             </div>  
          </div>
          <div class="layui-card-body ">
                    <form class="layui-form layui-form-pane valideform" method="POST" action="{{ route('admin.fup.store') }}">
                        {{ csrf_field() }}  
                        <div class="layui-form-item layui-form-text">
                            <label for="fupcontent" class="layui-form-label">跟进内容</label>
                            <div class="layui-input-block">
                                <textarea placeholder="请输入内容" id="fupcontent" name="fupcontent" class="layui-textarea"></textarea>
                            </div>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="hidden" name="customer_id" value="{{ $id }}">
                            <button class="layui-btn" lay-filter="add" lay-submit="">添加</button>
                        </div>
                    </form>
                </div>
            <div class="layui-card">
                
                <div class="layui-card-body ">
                    @if($lists->first() != null)
                        @foreach($lists as $list)
                            <ul class="layui-timeline">
                                <li class="layui-timeline-item">
                                    <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                                    <div class="layui-timeline-content layui-text">
                                        <h3 class="layui-timeline-title"><i class="layui-icon layui-icon-date"></i>{{ $list->updated_at }}</h3>
                                        <div class="caller-item"> 
                                        <div class="caller-main caller-fl">       
                                          <p><i class="layui-icon layui-icon-username"></i><strong>{{$user->name}}</strong> <i class="layui-icon layui-icon-cellphone"></i><em>{{$customer->phone}}</em></p>
                                          <p class="caller-adds"><i class="layui-icon layui-icon-reply-fill"></i>{{ $list->fupcontent }}</p> 
                                        </div>  
                                    </div>
                                </li>
                            </ul>
                        @endforeach
                    @else 
                        暂无跟进记录
                    @endif
                </div>
            </div>
            
        </div>
    </div>
    
</div> 
@stop
