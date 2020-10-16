@extends('admin.layout')

@section('title', '客户标签')

@section('content')

<div class="x-nav">
  <span class="layui-breadcrumb">
    <a href="">首页</a>
    <a href="">客户管理</a>
    <a>
      <cite>客户标签</cite></a>
  </span>
  <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
    <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5" method="get" action="{{ route('admin.tags.index') }}">
                        <div class="layui-input-inline layui-show-xs-block">
                            <input type="text" name="tagname" placeholder="请输入标签名" autocomplete="off" class="layui-input" value="{{ $tagname }}">
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-header">
                  <button class="layui-btn layui-btn-danger" onclick="delAll('{{ route('admin.tags.destroy', 0) }}', '{{ csrf_token() }}')"><i class="layui-icon"></i>批量删除</button>
                  <button class="layui-btn" onclick="xadmin.open('添加','{{ route('admin.tags.create') }}',500,200)"><i class="layui-icon"></i>添加</button>
              </div>
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                          <tr>
                            <th><input type="checkbox" name=""  lay-skin="primary" lay-filter="checkall"></th>
                            <th>序号</th>
                            <th>标签名</th>
                            <th>添加时间</th>
                            <th>操作</th>
                        </thead>
                        <tbody>
                          @foreach($lists as $list)
                            <tr>
                              <td><input type="checkbox" name="id[]" lay-skin="primary" value="{{ $list->id }}" class="checkbox"></td>
                              <td>{{ $loop->iteration }}</td>
                              <td>{{ $list->tagname }}</td>
                              <td>{{ $list->updated_at }}</td>
                              <td class="td-manage">
                                <button class="layui-btn layui-btn-xs" title="编辑" onclick="xadmin.open('编辑','{{ route('admin.tags.edit',$list->id) }}',500,200);" ><i class="layui-icon">&#xe642;</i>编辑</button>
                                <a class="layui-btn-danger layui-btn layui-btn-xs" title="删除" onclick="info_del('{{ route('admin.tags.destroy', $list->id) }}','{{ csrf_token() }}')">
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