@extends('admin.layout')

@section('title', '角色管理')

@section('content')
<div class="x-nav">
  <span class="layui-breadcrumb">
    <a href="">首页</a>
    <a href="">系统管理</a>
    <a>
      <cite>角色管理</cite></a>
  </span>
  <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
    <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
  <div class="layui-row layui-col-space15">
      <div class="layui-col-md12">
          <div class="layui-card">
              <div class="layui-card-body ">
                  <form class="layui-form layui-col-space5" action="{{ route('admin.roles.index') }}" method="get">
                      <div class="layui-inline layui-show-xs-block">
                          <input type="text" name="keywords"  placeholder="请输入角色名" autocomplete="off" class="layui-input" value="{{ $keywords }}">
                      </div>
                      <div class="layui-inline layui-show-xs-block">
                          <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                      </div>
                  </form>
              </div>
              <div class="layui-card-header">
                  <button class="layui-btn layui-btn-danger" onclick="delAll('{{ route('admin.roles.destroy', 0) }}', '{{ csrf_token() }}')"><i class="layui-icon"></i>批量删除</button>
                  <button class="layui-btn" onclick="xadmin.open('添加角色','{{ route('admin.roles.create') }}',900,600)"><i class="layui-icon"></i>添加</button>
              </div>
              <div class="layui-card-body ">
                  <table class="layui-table layui-form">
                    <thead>
                      <tr>
                        <th>
                          <input type="checkbox" name=""  lay-skin="primary" lay-filter="checkall">
                        </th>
                        <th>ID</th>
                        <th>角色名</th>
                        <th>拥有权限规则</th>
                        <th>描述</th>
                        <th>状态</th>
                        <th>操作</th>
                    </thead>
                    <tbody>
                      @foreach($list as $key=>$role)
                      <tr>
                        <td>
                          <input type="checkbox" name=""  lay-skin="primary" class="checkbox" value="{{ $role->id }}" @if($role->id < 7) disabled @endif>
                        </td>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                          @foreach($role->permissions as $val)
                          @if($loop->iteration < 4)
                          {{ $val->name }}、
                          @endif
                          @endforeach
                          @if($role->permissions->count() > 3)
                          ...
                          @endif
                        </td>
                        <td>{{ $role->desc }}</td>
                        <td class="td-status">
                          <input type="checkbox" name="status" lay-skin="switch" lay-text="开启|关闭" value="1" @if($role->status == 1) checked @endif data_id="{{ $role->id }}">
                        </td>
                        <td class="td-manage">
                          <button class="layui-btn layui-btn layui-btn-xs" onclick="xadmin.open('编辑','{{ route('admin.roles.edit', $role->id) }}',900,600)" ><i class="layui-icon">&#xe642;</i>编辑</button>
                          @if($role->id > 6)
                          <button class="layui-btn-danger layui-btn layui-btn-xs" onclick="info_del('{{ route('admin.roles.destroy', $role->id) }}', '{{ csrf_token() }}')" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
                          @endif
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
changeStatus("{{ route('admin.roles.update', 0) }}", "{{ csrf_token() }}")
</script>
@stop