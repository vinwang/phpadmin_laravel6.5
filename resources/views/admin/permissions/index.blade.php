@extends('admin.layout')

@section('title', '权限菜单')

@section('content')
<div class="x-nav">
  <span class="layui-breadcrumb">
    <a href="">首页</a>
    <a href="">系统配置</a>
    <a>
      <cite>权限菜单</cite></a>
  </span>
  <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
    <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">
                    <button class="layui-btn layui-btn-danger" onclick="delAll('{{ route('admin.permissions.destroy', 0) }}', '{{ csrf_token() }}')"><i class="layui-icon"></i>批量删除</button>
                    <button class="layui-btn" onclick="xadmin.open('添加菜单','{{ route('admin.permissions.create') }}',600,400)"><i class="layui-icon"></i>添加</button>
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                      <thead>
                        <tr>
                          <th>
                            <input type="checkbox" name=""  lay-skin="primary" lay-filter="checkall">
                          </th>
                          <th>权限名称</th>
                          <th>权限规则</th>
                          <th>是否启用</th>
                          <th>图标</th>
                          <th>排序</th>
                          <th>操作</th>
                      </thead>
                      <tbody>
                        @foreach($list as $per)
                        <tr>
                          <td>
                           <input type="checkbox" name="id[]" lay-skin="primary" value="{{ $per->id }}" class="checkbox">
                          </td>
                          <td>{{ $per->name }}</td>
                          <td>{{ $per->uri }}</td>
                          <td>
                            <input type="checkbox" name="status" lay-skin="switch" lay-text="开启|关闭" value="1" @if($per->status == 1) checked @endif data_id="{{ $per->id }}">
                          </td>
                          <td><i class="iconfont">{!! $per->icon !!}</i></td>
                          <td>{{ $per->sort }}</td>
                          <td class="td-manage">
                            <button class="layui-btn layui-btn layui-btn-xs" onclick="xadmin.open('编辑','{{ route('admin.permissions.edit', $per->id) }}',600,400)" ><i class="layui-icon">&#xe642;</i>编辑</button> 
                            <button class="layui-btn-danger layui-btn layui-btn-xs" onclick="info_del('{{ route('admin.permissions.destroy', $per->id) }}', '{{ csrf_token() }}')" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
                          </td>
                        </tr>
                        @foreach($per->permissions as $val)
                        <tr>
                          <td>
                           <input type="checkbox" name="id[]"  lay-skin="primary" value="{{ $val->id }}" class="checkbox">
                          </td>
                          <td> <i class="layui-icon">&#xe602;</i> {{ $val->name }}</td>
                          <td>{{ $val->uri }}</td>
                          <td>
                            <input type="checkbox" name="status" lay-skin="switch" lay-text="开启|关闭" value="1" @if($val->status == 1) checked @endif data_id="{{ $val->id }}">
                          </td>
                          <td><i class="iconfont">{!! $val->icon !!}</i></td>
                          <td>{{ $val->sort }}</td>
                          <td class="td-manage">
                            <button class="layui-btn layui-btn layui-btn-xs" onclick="xadmin.open('编辑','{{ route('admin.permissions.edit', $val->id) }}',600,400)" ><i class="layui-icon">&#xe642;</i>编辑</button> 
                            <button class="layui-btn-danger layui-btn layui-btn-xs" onclick="info_del('{{ route('admin.permissions.destroy', $val->id) }}', '{{ csrf_token() }}')" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
                          </td>
                        </tr>
                        @foreach($val->permissions as $v)
                        <tr>
                          <td>
                           <input type="checkbox" name="id[]"  lay-skin="primary" value="{{ $v->id }}" class="checkbox">
                          </td>
                          <td> <i class="layui-icon">&#xe602;</i><i class="layui-icon">&#xe602;</i> {{ $v->name }}</td>
                          <td>{{ $v->uri }}</td>
                          <td>
                            <input type="checkbox" name="status" lay-skin="switch" lay-text="开启|关闭" value="1" @if($v->status == 1) checked @endif data_id="{{ $v->id }}">
                          </td>
                          <td><i class="iconfont">{!! $v->icon !!}</i></td>
                          <td>{{ $v->sort }}</td>
                          <td class="td-manage">
                            <button class="layui-btn layui-btn layui-btn-xs" onclick="xadmin.open('编辑','{{ route('admin.permissions.edit', $v->id) }}',600,400)" ><i class="layui-icon">&#xe642;</i>编辑</button> 
                            <button class="layui-btn-danger layui-btn layui-btn-xs" onclick="info_del('{{ route('admin.permissions.destroy', $v->id) }}', '{{ csrf_token() }}')" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
                          </td>
                        </tr>
                        @endforeach
                        @endforeach
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
<script>
changeStatus("{{ route('admin.permissions.update', 0) }}", "{{ csrf_token() }}")
</script>
@stop