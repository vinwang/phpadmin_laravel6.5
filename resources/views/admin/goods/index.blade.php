@extends('admin.layout')

@section('title', '业务种类')

@section('content')

<div class="x-nav">
  <span class="layui-breadcrumb">
    <a href="">首页</a>
    <a href="">订单管理</a>
    <a>
      <cite>业务种类</cite></a>
  </span>
  <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
    <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5" method="get" action="{{ route('admin.goods.index') }}">
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="name" placeholder="请输入业务种类名称" autocomplete="off" class="layui-input" value="{{ $name }}">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-header">
                  <button class="layui-btn layui-btn-danger" onclick="delAll('{{ route('admin.goods.destroy', 0) }}', '{{ csrf_token() }}')"><i class="layui-icon"></i>批量删除</button>
                  <button class="layui-btn" onclick="xadmin.open('添加业务种类','{{ route('admin.goods.create') }}',600,400)"><i class="layui-icon"></i>添加</button>
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                          <tr>
                            <th><input type="checkbox" name=""  lay-skin="primary" lay-filter="checkall"></th>
                            <th>序号</th>
                            <th>业务种类名称</th>
                            <th>说明</th>
                            <th>创建者</th>
                            <th>是否激活</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </thead>
                        <tbody>
                          @foreach($lists as $list)
                            <tr>
                              <td><input type="checkbox" name="id[]" lay-skin="primary" value="{{ $list->id }}" class="checkbox"></td>
                              <td>{{ $loop->iteration }}</td>
                              <td>{{ $list->name }}</td>
                              <td>{{ $list->description }}</td>
                              <td>{{ $user[$list->uid] }}</td>
                              <td>
                                <input type="checkbox" name="status" lay-skin="switch" lay-text="开启|关闭" value="1" @if($list->status == 1) checked @endif data_id="{{ $list->id }}">
                              </td>
                              <td>{{ $list->updated_at }}</td>
                              <td class="td-manage">
                                <button class="layui-btn layui-btn-xs" title="编辑" onclick="xadmin.open('编辑业务种类','{{ route('admin.goods.edit',$list->id) }}',600,400);" ><i class="layui-icon">&#xe642;</i>编辑</button>
                                <a class="layui-btn-danger layui-btn layui-btn-xs" title="删除" onclick="info_del('{{ route('admin.goods.destroy', $list->id) }}','{{ csrf_token() }}')">
                                  <i class="layui-icon">&#xe640;</i>删除
                                </a>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                </div>
                <div class="layui-card-body ">
                    <div class="page">
                        <div>
                          {{ $lists->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
@stop
@section('javascript')
<script>
changeStatus("{{ route('admin.goods.update', 0) }}", "{{ csrf_token() }}")
</script>
@stop