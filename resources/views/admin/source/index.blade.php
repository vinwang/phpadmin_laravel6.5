@extends('admin.layout')

@section('title', '客户来源')

@section('content')

    <div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="">首页</a>
            <a href="">客户管理</a>
            <a>
                <cite>客户来源</cite>
            </a>
        </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
        </a>
    </div>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <form class="layui-form layui-col-space5" method="get" action="{{ route('admin.source.index') }}">
                            <div class="layui-input-inline layui-show-xs-block">
                                <input type="text" name="name" value="{{ $name }}" placeholder="搜索记录" autocomplete="off" class="layui-input"></div>
                            <div class="layui-input-inline layui-show-xs-block">
                                <button class="layui-btn" lay-submit="" lay-filter="sreach">
                                    <i class="layui-icon">&#xe615;</i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="layui-card-header">
                        <button class="layui-btn layui-btn-danger" onclick="delAll('{{ route('admin.source.destroy', 0) }}', '{{ csrf_token() }}')">
                            <i class="layui-icon"></i>批量删除
                        </button>
                        <button class="layui-btn" onclick="xadmin.open('添加客户来源','{{ route('admin.source.create') }}', 500, 200)">
                            <i class="layui-icon"></i>添加
                        </button>
                    </div>
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                            <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name=""  lay-skin="primary" lay-filter="checkall">
                                </th>
                                <th>序号</th>
                                <th>来源名</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($lists as $k => $list)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="id[]" lay-skin="primary" value="{{ $list->id }}" class="checkbox">
                                    </td>
                                    <td>{{ $k+1 }}</td>
                                    <td>{{ $list->name }}</td>
                                    <td>{{ $list->created_at }}</td>
                                    <td>
                                        <button class="layui-btn layui-btn layui-btn-xs"  onclick="xadmin.open('编辑客户来源', '{{ route('admin.source.edit', $list->id) }}', 500, 200)" ><i class="layui-icon">&#xe642;</i>编辑</button>
                                        <button class="layui-btn-danger layui-btn layui-btn-xs"  onclick="info_del('{{ route('admin.source.destroy', $list->id) }}','{{ csrf_token() }}')" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
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
