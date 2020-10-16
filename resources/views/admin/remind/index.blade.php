@extends('admin.layout')

@section('title', '提醒列表')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
       <div class="layui-col-md12">
          <div class="layui-card">
                <div class="layui-card-body ">
                  <form class="layui-form layui-col-space5" action="{{ route('admin.remind.index') }}" 
                  method="get">
                      <div class="layui-inline layui-show-xs-block">
                          <input class="layui-input"  autocomplete="off" placeholder="开始日" name="start" id="start" value="{{ $start }}">
                      </div>
                      <div class="layui-inline layui-show-xs-block">
                          <input class="layui-input"  autocomplete="off" placeholder="截止日" name="end" id="end" value="{{ $end }}">
                      </div>
                      <div class="layui-inline layui-show-xs-block">
                          <input type="text" name="keywords" placeholder="提醒内容" autocomplete="off" class="layui-input" value="{{ $keywords }}">
                      </div>
                      <div class="layui-inline layui-show-xs-block">
                          <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                      </div>
                  </form>
              </div>
              <div class="layui-card-header">
                    <button class="layui-btn layui-btn-danger" onclick="delAll('{{ route('admin.remind.destroy', 0) }}', '{{ csrf_token() }}')"><i class="layui-icon"></i>批量删除</button>
                  <button class="layui-btn" onclick="xadmin.open('新建提醒','{{ route('admin.remind.create') }}',760,600)"><i class="layui-icon"></i>新建提醒</button>
              </div>
              <div class="layui-card-body ">
                  <table class="layui-table layui-form" lay-skin="line">
                    <thead>
                      <tr>
                        <th>
                          <input type="checkbox" name="" lay-filter="checkall"  lay-skin="primary">
                        </th>
                        <th>提醒时间</th>
                        <th>提醒内容</th>
                        <th>操作</th>
                    </thead>
                    <tbody>
                    @foreach($list as $info)
                      <tr
                      @if($info->status == 2)
                      style="background-color:#f2f2f2;"
                      @endif> 
                        <td>
                          <input type="checkbox" name="id" lay-skin="primary" class="checkbox" value="{{ $info->id }}">
                          @if($info->status == 0)
                          <a href="javascript:;" title="未提醒"><i class="layui-icon layui-icon-notice" style="color:red;"></i>
                          </a>
                          @elseif($info->status == 1)
                          <a href="javascript:;" title="已完成"><i class="layui-icon layui-icon-notice" style="color:green;"></i>
                          </a>
                          @endif
                        </td>
                        <td> 
                          <a href="javascript:;" onclick="xadmin.open('查看提醒','{{ route('admin.remind.show', $info->id) }}',760,600)">
                          {{ $info->remind_time }}
                        </a>
                        </td>
                        <td>
                          <a href="javascript:;" onclick="xadmin.open('查看提醒','{{ route('admin.remind.show', $info->id) }}',760,600)">
                          {{ $info->content }}
                          <br>
                          {{ $info->customers->count()}}位客户：
                          @foreach($info->customers as $cm)
                          {{ $cm->company }}
                          @if(!$loop->last)
                          、
                          @endif
                          @endforeach
                        </a>
                        </td>
                        <td class="td-manage">
                        @if($info->status == 0)  
                          <button class="layui-btn layui-btn layui-btn-xs" onclick="xadmin.open('编辑提醒','{{ route('admin.remind.edit', $info->id) }}',760,600)" ><i class="layui-icon">&#xe642;</i>编辑</button>
                        @endif
                          <button class="layui-btn-danger layui-btn layui-btn-xs" onclick="info_del('{{ route('admin.remind.destroy', $info->id) }}', '{{ csrf_token() }}')" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
                        </td>
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
layui.use('laydate', function(){
  var laydate = layui.laydate;
  laydate.render({
    elem: '#start',
    type: 'datetime'
  });

  //执行一个laydate实例
  laydate.render({
    elem: '#end',
    type: 'datetime'
  });
});
</script>
@stop