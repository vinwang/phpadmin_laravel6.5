@extends('admin.layout')

@section('title', '系统设置')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
       <div class="layui-col-md12">
           <div class="layui-card">
                 <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
                  <ul class="layui-tab-title">
                    <li><a href="{{ route('admin.customer.show',$id) }}">客户详情</a></li> 
                    <li class="layui-this"><a href="{{ route('admin.bank.index',['id'=>$id]) }}">客户银行卡</a></li>
                    <li><a href="{{ route('admin.fup.index',['id'=>$id]) }}">跟进记录</a></li>
                    <li><a href="{{ route('admin.customer.record',['id'=>$id]) }}">客户领取分配记录</a></li>
                  </ul>
                  <div class="layui-tab-content"></div>
                 </div>  
              </div>
                    <div class="layui-card"> 
                        <div class="layui-card-header"> 
                            <button class="layui-btn" onclick="xadmin.open('添加用户','{{ route('admin.bank.create',['id'=>$id]) }}',800,700)"><i class="layui-icon"></i>添加</button>
                        </div>
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                              <thead>
                                <tr> 
                                  <th>ID</th>
                                  <th>开户行名称</th>
                                  <th>开户行账号</th>
                                  <th>开户行地址</th>
                                  <th>社会信用代码</th> 
                                  <th>编辑时间</th> 
                                  <th>操作</th>
                              </thead>
                              <tbody>
                              @foreach($list as $arr)
                                <tr> 
                                  <td>{{ $arr->id }}</td>
                                  <td>{{ $arr->bankname }}</td>
                                  <td>{{ $arr->banknumber }}</td>
                                  <td>{{ $arr->bankadd }}</td> 
                                  <td>{{ $arr->companycode }}</td>  
                                  <td>{{ $arr->updated_at }}</td>
                                  <td class="td-manage">   
                                    <button class="layui-btn layui-btn layui-btn-xs" onclick="xadmin.open('编辑客户','{{ route('admin.bank.edit',$arr->id) }}',600,500)" ><i class="layui-icon">&#xe642;</i>编辑</button>

                                    <a class="layui-btn-danger layui-btn layui-btn-xs" title="删除" onclick="info_del('{{ route('admin.bank.destroy', $arr->id) }}','{{ csrf_token() }}')">
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
                                 {{ $list->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
</div>
@stop