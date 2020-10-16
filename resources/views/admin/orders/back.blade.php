@extends('admin.layout')

@section('title', '回款记录')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                @if($roles == 5 || $roles == 1 || ($roles == 2 && $admin->grade->id == 1))
                <div class="layui-card-body ">
                    <form class="layui-form layui-form-pane valideform" method="POST" action="{{ route('admin.orders.back') }}">
                        {{ csrf_field() }}
                        @foreach($stages as $val)
                        <div class="layui-form-item" id="stages">
                            <label for="stages" class="layui-form-label"><span class="x-red">*</span>第{{ $loop->iteration }}期金额</label>
                            <div class="layui-input-inline">
                                <input type="text" name="stages[]" autocomplete="off" class="layui-input" value="{{ $val['stages'] }}" readonly>
                                <input type="hidden" name="rece_id[]" value="{{ isset($val['rece_id']) ? $val['rece_id'] : 0 }}">
                            </div>
                            
                            <label for="billing_time" class="layui-form-label">开票时间{{ $loop->iteration }}</label>
                            <div class="layui-input-inline">
                                <input type="text" id="billing_time{{ $loop->iteration }}" name="billing_time[]" autocomplete="off" class="layui-input" value="{{ $val['billing_time'] }}">
                            </div>

                            <label for="collection_time" class="layui-form-label">收款时间{{ $loop->iteration }}</label>
                            <div class="layui-input-inline">
                                <input type="text" id="collection_time{{ $loop->iteration }}" name="collection_time[]" autocomplete="off" class="layui-input" value="{{ $val['collection_time'] }}">
                            </div>
                        </div>
                        @endforeach
                        @if($roles == 5 || $roles == 1)       
                        <div class="layui-form-item layui-form-text">
                            <label for="content" class="layui-form-label">开票备注</label>
                            <div class="layui-input-block">
                                <textarea placeholder="请输入开票备注" id="content" name="content" class="layui-textarea">{{ $orders->content }}</textarea>
                            </div>
                            <label for="remark" class="layui-form-label">回款备注</label>
                            <div class="layui-input-block">
                                <textarea placeholder="请输入回款备注" id="remark" name="remark" class="layui-textarea"></textarea>
                            </div>
                        </div>
                        
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="hidden" name="id" value="{{ $id }}">
                            <button class="layui-btn" lay-filter="add" lay-submit="">提交</button>
                        </div>
                        @endif
                    </form>
                </div>
                @endif
                <div class="layui-card-body ">
                    @if($orders->receivables()->count())
                        @foreach($orders->receivables as $list)
                            <ul class="layui-timeline">
                                <li class="layui-timeline-item">
                                    <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                                    <div class="layui-timeline-content layui-text"><hr>
                                        <h3 class="layui-timeline-title">{{ $list->created_at }}</h3>
                                        <p>
                                          回款金额：{{ $list->money }}元<br>
                                          回款时间：{{ $list->back_time }}<br>
                                          备注： {{ $list->remark ? $list->remark : '无' }}
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        @endforeach
                        @foreach($orders->refund as $val)
                            <ul class="layui-timeline">
                                <li class="layui-timeline-item">
                                    <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                                    <div class="layui-timeline-content layui-text"><hr>
                                        <h3 class="layui-timeline-title">{{ $val->created_at }}</h3>
                                        <p>
                                          退款金额：{{ $val->refund_money }}元<br>
                                          退款时间：{{ $val->refund_time }}<br>
                                          备注： {{ $val->remark ? $val->remark : '无' }}
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        @endforeach
                    @else 
                        暂无回款数据
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</div> 
@stop
@section('javascript')
<script type="text/javascript">
layui.use(['laydate'], function(){
    var laydate = layui.laydate;
    @foreach($stages as $val)
        //执行一个laydate实例
        laydate.render({
            elem: '#billing_time{{ $loop->iteration }}',
            type: 'datetime',
            trigger: 'click'
        });
        //执行一个laydate实例
        laydate.render({
            elem: '#collection_time{{ $loop->iteration }}',
            type: 'datetime',
            trigger: 'click'
        });
    @endforeach
});
</script>
@stop