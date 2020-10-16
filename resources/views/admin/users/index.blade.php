@extends('admin.layout')

@section('title', '用户列表')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
       <div class="layui-col-md12">
          <div class="layui-card">
              <div class="layui-card-body ">
                  <form class="layui-form layui-col-space5" action="{{ route('admin.users.index')}}" method="get">
                      <div class="layui-inline layui-show-xs-block">
                          <input type="text" name="keywords"  placeholder="请输入用户名" autocomplete="off" class="layui-input" value="{{ $keywords }}">
                      </div>
                      <div class="layui-inline layui-show-xs-block">
                          <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                      </div>
                  </form>
              </div>
              <div class="layui-card-header">
                    <button class="layui-btn layui-btn-danger" onclick="delAll('{{ route('admin.users.destroy', 0) }}', '{{ csrf_token() }}')"><i class="layui-icon"></i>批量删除</button>
                  <button class="layui-btn" onclick="xadmin.open('添加用户','{{ route('admin.users.create') }}',700,500)"><i class="layui-icon"></i>添加</button>
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
                        <th>所属部门</th>
                        <th>用户等级</th>
                        <th>创建时间</th>
                        <th>修改时间</th>
                        <th>操作</th>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                      <tr>
                        <td>
                          <input type="checkbox" name="id" lay-skin="primary" class="checkbox" value="{{ $user->id }}" @if($user->id == 1) disabled @endif>
                        </td>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->roles->count() ? $user->roles->first()->name : '' }}</td>
                        <td>{{ $user->grade ? $user->grade->name : ''}}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>{{ $user->updated_at }}</td>
                        <td class="td-manage">  
                          <button class="layui-btn layui-btn layui-btn-xs" onclick="xadmin.open('编辑客户','{{ route('admin.users.edit', $user->id) }}',900,600)" ><i class="layui-icon">&#xe642;</i>编辑</button>
                          @if($user->id != 1)
                          <button class="layui-btn-danger layui-btn layui-btn-xs" onclick="info_del('{{ route('admin.users.destroy', $user->id) }}', '{{ csrf_token() }}')" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
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
                       {{ $users->links() }}
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
</div>
@stop