@extends('admin.layout')

@section('title', '等级列表')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
       <div class="layui-col-md12">
          <div class="layui-card">
              <div class="layui-card-body ">
                  <form class="layui-form layui-col-space5" action="{{ route('admin.grade.index')}}" method="get">
                      <div class="layui-inline layui-show-xs-block">
                          <input type="text" name="keywords"  placeholder="请输入等级" autocomplete="off" class="layui-input" value="{{ $keywords }}">
                      </div>
                      <div class="layui-inline layui-show-xs-block">
                          <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                      </div>
                  </form>
              </div>
              <div class="layui-card-header">
                    <button class="layui-btn layui-btn-danger" onclick="delAll('{{ route('admin.grade.destroy', 0) }}', '{{ csrf_token() }}')"><i class="layui-icon"></i>批量删除</button>
                  <button class="layui-btn" onclick="xadmin.open('添加用户','{{ route('admin.grade.create') }}',500,200)"><i class="layui-icon"></i>添加</button>
              </div>
              <div class="layui-card-body ">
                  <table class="layui-table layui-form">
                    <thead>
                      <tr>
                        <th>
                          <input type="checkbox" name="" lay-filter="checkall"  lay-skin="primary">
                        </th>
                        <th>ID</th>
                        <th>用户名</th>
                        <th>创建时间</th>
                        <th>修改时间</th>
                        <th>操作</th>
                    </thead>
                    <tbody>
                    @foreach($grade as $g)
                      <tr>
                        <td>
                          <input type="checkbox" name="id" lay-skin="primary" class="checkbox" value="{{ $g->id }}" @if($g->id == 1) disabled @endif>
                        </td>
                        <td>{{ $g->id }}</td>
                        <td>{{ $g->name }}</td>
                        <td>{{ $g->created_at }}</td>
                        <td>{{ $g->updated_at }}</td>
                        <td class="td-manage">  
                          <button class="layui-btn layui-btn layui-btn-xs" onclick="xadmin.open('编辑等级','{{ route('admin.grade.edit', $g->id) }}',500,200)" ><i class="layui-icon">&#xe642;</i>编辑</button>
                          @if($g->id != 1)
                          <button class="layui-btn-danger layui-btn layui-btn-xs" onclick="info_del('{{ route('admin.grade.destroy', $g->id) }}', '{{ csrf_token() }}')" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
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
                       {{ $grade->links() }}
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
</div>
@stop