@extends('admin.layout')

@section('title', '业务节点列表')

@section('content')

<div class="x-nav">
  <span class="layui-breadcrumb">
    <a href="">首页</a>
    <a href="">订单管理</a>
    <a href="">订单列表</a>
    <a>
      <cite>业务节点状态列表</cite></a>
  </span>
  <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
    <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                          <tr>
                            <th>业务种类</th>
                            <th>业务节点</th>
                            <th>是否评测</th>
                            <th>是否含网</th>
                            <th>业务状态</th>
                            <th>操作人</th>
                            <th>操作时间</th>
                            @if($roles == 4 || $roles == 1)
                            <th>操作</th>
                            @endif
                        </thead>
                        <tbody>
                          @foreach($list as $val)
                            <tr>
                              <td><a href="javascript:;"  onclick="xadmin.open('业务节点状态记录','{{ route('admin.orders.detail',['id'=>$val->id]) }}',900,500);">{{ $good[$val->good_id] }}</a></td>
                              <td>{{ $province[$val->provinces] }}</td>
                              <td>{{ $val->review == 1 ? '是' : '否' }}</td>
                              <td>{{ $good[$val->good_id]=='ISP' ? ($val->network == 1 ? '含网' : '不含网') : '' }}</td>
                              <td>
                                <a href="javascript:;" value="{{ $val->content }}" class="process">
                                  @foreach(explode(',',$val->process) as $v)
                                    <span style="color: @if($v == 15) red @elseif($v == 18) green @endif">{{ $jsprocess[$v] }}</span><br>
                                  @endforeach
                                </a>
                              </td>
                              <td>
                                @if($val->goodProvincesRecord->count())
                                  {{ $val->goodProvincesRecord->first()->user->nickname ?: $val->goodProvincesRecord->first()->user->name }}
                                @else
                                  {{ $user[$val->user_id]['nickname'] ? $user[$val->user_id]['nickname'] : $user[$val->user_id]['name'] }}
                                @endif
                              </td>
                              <td>{{ $val->updated_at }}</td>
                              @if($roles == 4 || $roles == 1)
                              <td class="td-manage">
                                  <button class="layui-btn layui-btn-xs" title="编辑" onclick="xadmin.open('编辑业务状态','{{ route('admin.orders.processedit',['id'=>$val->id]) }}',900,500);" ><i class="layui-icon">&#xe642;</i>编辑</button>
                              </td>
                              @endif
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                </div>
                <div class="layui-card-body ">
                    <div class="page">
                        <div>
                          {{ $list->links() }}
                        </div>
                    </div>
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
    $('.process').on({
        mouseenter:function(){
            var that = this;
            if($(this).attr('value')) tips =layer.tips("<span>"+$(this).attr('value')+"</span>",that,{tips:[2,'#009688'],time:0,area: 'auto',maxWidth:500});
        },
        mouseleave:function(){
            layer.close(tips);
        }
    });
})
</script>
@stop