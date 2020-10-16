@extends('admin.layout')

@section('title', '业务终止')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-form-pane valideform" method="POST" action="{{ route('admin.orders.stop', $id) }}">
                        {{ csrf_field() }}
                        <div class="layui-form-item">
                          <label for="phone" class="layui-form-label">
                              <span class="x-red"></span>订单状态
                          </label>
                          <div class="layui-inline layui-show-xs-block" style="width: 400px;">
                              <select name="status" xm-select="select16_6" datatype="*">
                                @if($role == 1)
                                  @foreach($status as $key => $arr)
                                    <option value="{{ $key }}">{{ $arr }}</option>
                                  @endforeach  
                                @elseif($role == 2)
                                  @if(in_array($orderstatus->status, [0,3,5]))
                                    @foreach($state as $key => $arr)
                                      <option value="{{ $key }}">{{ $arr }}</option>
                                    @endforeach 
                                  @else
                                    @foreach($status as $key => $arr)
                                      <option value="{{ $key }}">{{ $arr }}</option>
                                    @endforeach
                                  @endif
                                @elseif($role == 5)
                                  @foreach($state as $key => $arr)
                                    <option value="{{ $key }}">{{ $arr }}</option>
                                  @endforeach
                                @endif
                            </select> 
                          </div>
                        </div>

                        @if($orderstatus->remarks && !in_array($orderstatus->status, [0,1,2]))
                        <div class="layui-form-item">
                          <label for="" class="layui-form-label">备注</label>
                          <div class="layui-input-inline">
                            <input type="text" autocomplete="off" class="layui-input" value="{{ $orderstatus->remarks }}" disabled style="width: 400px;">
                          </div>
                        </div>
                        @endif
                        
                        <div class="layui-form-item layui-form-text">
                            <label for="remark" class="layui-form-label">@if(in_array($role, [1,2])) 业务说明 @else 审核说明 @endif</label>
                            <div class="layui-input-block">
                                <textarea placeholder="请输入内容" id="remark" name="remark" class="layui-textarea"></textarea>
                            </div>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-filter="add" lay-submit="">提交</button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> 
@stop
@section('javascript')
<script type="text/javascript">

</script>
@stop