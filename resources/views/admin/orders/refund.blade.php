@extends('admin.layout')

@section('title', '回款记录')

@section('body_class', 'index')
<style>
  .verify{float: right;padding-right: 100px;width: 100px;height: 30px;}
</style>
@section('content')
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                @if($roles == 2 || $roles == 1)
                <div class="layui-card-body ">
                    <form class="layui-form layui-form-pane valideform" method="POST" action="{{ route('admin.orders.refund') }}">
                        {{ csrf_field() }}
                        <div class="layui-form-item">
                            <label for="money" class="layui-form-label"><span class="x-red">*</span>退款金额</label>
                            <div class="layui-input-inline">
                                <input type="text" onkeyup="value=value.replace(/^\D*(\d*(?:\.\d{0,2})?).*$/g, '$1')" id="refund_money" name="refund_money" datatype="*" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">
                                <span class="x-red">可退款金额：{{ $receivables_money }}元&nbsp;/&nbsp;合同金额：{{ $plannedamt }}元</span>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label for="refund_time" class="layui-form-label"><span class="x-red">*</span>退款时间</label>
                            <div class="layui-input-inline">
                                <input type="text" id="refund_time" name="refund_time" datatype="*" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                                
                        <div class="layui-form-item layui-form-text">
                            <label for="remark" class="layui-form-label">备注</label>
                            <div class="layui-input-block">
                                <textarea placeholder="请输入内容" id="remark" name="remark" class="layui-textarea"></textarea>
                            </div>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="hidden" name="id" value="{{ $id }}">
                            <input type="hidden" name="retire" value="{{ $receivables_money }}">
                            <button class="layui-btn" lay-filter="add" lay-submit="">提交</button>
                        </div>
                    </form>
                </div>
                @endif
                <div class="layui-card-body ">
                  @if($lists->first() != null)
                    @foreach($lists as $list)
                        <form action="{{ route('admin.orders.refund',['money_id'=>$list->id]) }}"  method="POST"  class="layui-form layui-form-pane valideform">
                            <ul class="layui-timeline">
                                <li class="layui-timeline-item">
                                    <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                                    <div class="layui-timeline-content layui-text"><hr>
                                        <h3 class="layui-timeline-title">{{ $list->created_at }}</h3>
                                        <p>
                                          退款金额： 
                                          @if($roles == 2 || $roles == 1)
                                          <input type="text" onkeyup="value=value.replace(/^\D*(\d*(?:\.\d{0,2})?).*$/g, '$1')" name="refund_money" value="{{ $list->refund_money }}" onblur="moneySubmit('{{ route('admin.orders.refund', ['money_id'=>$list->id]) }}','{{ csrf_token() }}',this.value);" style="width: 55px;">
                                          @else
                                            {{ $list->refund_money }}
                                          @endif
                                          元<br>
                                          退款时间：{{ $list->refund_time }}@if($roles == 2 || $roles == 1 || $list->status)<span class="verify" value="{{ $list->reason }}" style="color: @if($list->status == 0) blue @elseif($list->status == 1) green @else red @endif ;" >@if($list->status == 0) 待审核 @elseif($list->status == 1) 审核通过 @else 审核未通过 @endif</span>@elseif($roles == 5) <span class="verify"><a class="layui-btn" onclick="xadmin.open('退款审核','{{ route('admin.orders.examine',['id'=>$list->id]) }}',500,300)">审核</a></span> @endif<br>
                                          备注： {{ $list->remark ? $list->remark : '无' }}
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </form>
                    @endforeach
                  @else 
                      暂无退款数据
                  @endif
                </div>
            </div>
            
        </div>
    </div>
</div> 
@stop
@section('javascript')
<script type="text/javascript">
$(function(){
  var tips;
  $('.verify').on({
      mouseenter:function(){
          var that = this;
          if($(this).attr('value')) tips =layer.tips("<span>"+$(this).attr('value')+"</span>",that,{tips:[2,'#009688'],time:0,area: 'auto',maxWidth:500});
      },
      mouseleave:function(){
          layer.close(tips);
      }
  });
});

layui.use(['laydate'], function(){
  var laydate = layui.laydate;

  //执行一个laydate实例
  laydate.render({
      elem: '#refund_time',
      type: 'datetime',
      trigger: 'click'
  });
});

function moneySubmit(uri,token,money){
    var numid = "{{ $id }}";
    layer.confirm('确认要修改金额吗？',function(index){
        $.ajax({
          type: "POST",
          url: uri,
          headers: {'X-CSRF-TOKEN': token},
          data: {refund_money:money,numid:numid},
          success: function(result){
            if(result.code > 0){
              layer.msg(result.msg, {icon: 5});
              return false;
            }
            else{
              location.reload();
            }
          }
        });
    });
}
</script>
@stop