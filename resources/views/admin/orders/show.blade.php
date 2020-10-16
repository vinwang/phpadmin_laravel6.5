@extends('admin.layout')

@section('title', '详情')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">订单详情</li>
        <li>客户详情</li>
        <li>流转记录</li>
    </ul>
    <div class="layui-row layui-form-pane layui-tab-content layui-form">
        <div class="layui-tab-item layui-show "> 
            <div class="layui-form-item">
                <label class="layui-form-label">订单编号:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $list->order_num }}" disabled>
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">客户名称:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $customer[$list->customer_id]['company'] }}" disabled>
                </div>
                @if($roles == 4)
                @if($list->receivables->count())
                <div class="layui-input-inline">
                    <input type="checkbox" title="预付款后发货" name="shipped" lay-skin="primary" value="1" @if($list->shipped) checked @endif disabled>
                </div>
                @endif
                @else
                <div class="layui-input-inline">
                    <input type="checkbox" title="预付款后发货" name="shipped" lay-skin="primary" value="1" @if($list->shipped) checked @endif disabled>
                </div>
                @endif
            </div>
            
            @foreach($goodProvince as $key=>$val)
            <div class="layui-form-item">
                <label class="layui-form-label">业务种类:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $good[$key] }}" disabled>
                </div>
                <label class="layui-form-label">分布节点:</label>
                <div class="layui-input-inline">
                    <textarea class="layui-textarea" disabled>@foreach($val as $v){{ $province[$v['id']] }}@if($v['review'])（评测）@endif @if($good[$key] == 'ISP') @if($v['network'])（含网）@endif @endif&#xd;@endforeach</textarea>
                </div>
            </div>
            @endforeach

            <div class="layui-form-item">
              <label class="layui-form-label">归属人:</label>
              <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $user[$list->user_id]['nickname'] ? $user[$list->user_id]['nickname'] : $user[$list->user_id]['name'] }}" disabled>
              </div>
            </div>
            @if($roles == 1 || $roles == 2 || $roles == 5)
            <div class="layui-form-item">
                <label class="layui-form-label">合同金额:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="￥{{ number_format( $list->plannedamt) }}" disabled>
                </div>
            </div>
            
            @foreach($stagesarr as $val)
            <div class="layui-form-item">
                <label class="layui-form-label">第{{ $loop->iteration }}期金额:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="￥{{ number_format($val['stages']) }}" disabled>
                </div>
                @if($roles == 5 || $roles == 1)
                    <label for="billing_time" class="layui-form-label">开票时间{{ $loop->iteration }}</label>
                    <div class="layui-input-inline">
                        <input type="text" id="billing_time{{ $loop->iteration }}" autocomplete="off" class="layui-input" value="{{ $val['billing_time'] }}" disabled>
                    </div>

                    <label for="collection_time" class="layui-form-label">收款时间{{ $loop->iteration }}</label>
                    <div class="layui-input-inline">
                        <input type="text" id="collection_time{{ $loop->iteration }}" autocomplete="off" class="layui-input"  value="{{ $val['collection_time'] }}" disabled>
                    </div>
                @endif
            </div>
            @endforeach
            <div class="layui-form-item">
                <label class="layui-form-label">商务费用:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="￥{{ $list->cost ? number_format($list->cost->business_cost) : '' }}" disabled>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">外包成本:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="￥{{ $list->cost ? number_format($list->cost->outsourcing_cost) : '' }}" disabled>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">加急费用:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="￥{{ $list->cost ? number_format($list->cost->urgent_cost) : '' }}" disabled>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">其他成本:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="￥{{ $list->cost ? number_format($list->cost->xiaoshou_other_cost) : '' }}" disabled>
                </div>
            </div>
            @endif
            @if($roles == 1 || $roles == 4 || $roles == 5)
            <div class="layui-form-item">
                <label class="layui-form-label">设备成本:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="￥{{ $list->cost ? number_format($list->cost->equipment_cost) : '' }}" disabled>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">托管费用:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="￥{{ $list->cost ? number_format($list->cost->trusteeship_cost) : '' }}" disabled>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">软件成本:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="￥{{ $list->cost ? number_format($list->cost->software_cost) : '' }}" disabled>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">技术其他成本:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="￥{{ $list->cost ? number_format($list->cost->jishu_other_cost) : '' }}" disabled>
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">技术成本备注:</label>
                <div class="layui-input-block">
                    <textarea class="layui-textarea" disabled>{{ $list->cost ? $list->cost->jishu_remarks : '' }}</textarea>
                </div>
            </div>
            @endif
            @if($roles == 3 || $roles == 1)
            <div class="layui-form-item">
                <label class="layui-form-label">订单类型:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $orderType[$list->order_type] }}" disabled>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">增值服务:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $list->increment }}" disabled>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">许可证号:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $list->licence }}" disabled>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">证书起始时间:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $list->licence_start ? date('Y-m-d',$list->licence_start) : '' }}" disabled>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">证书截止时间:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $list->licence_end ? date('Y-m-d',$list->licence_end) : '' }}" disabled>
                </div>
            </div>
            @endif
            @if($roles == 1 || $roles == 2)
            <div class="layui-form-item">
                <label class="layui-form-label">创建时间:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $list->created_at }}" disabled>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">修改时间:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $list->updated_at }}" disabled>
                </div>
            </div>

            @if($contract)
                <div class="layui-form-item">
                    <label class="layui-form-label">合同标题:</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" value="{{ $contract->title }}" disabled>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">合同编号:</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" value="{{ $contract->number }}" disabled>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">开始时间:</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" value="{{ $contract->starttime ? date('Y-m-d',$contract->starttime) : '' }}" disabled>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">截止时间:</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" value="{{ $contract->endtime ? date('Y-m-d',$contract->endtime) : '' }}" disabled>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">合同备注:</label>
                    <div class="layui-input-block">
                        <textarea class="layui-textarea" disabled>{{ $contract->remark }}</textarea>
                    </div>
                </div> 
                @if($picture)
                    <div class="layui-form-item">
                        <label class="layui-form-label">图片:</label>
                        <div>
                            @foreach($picture as $pic)
                                <a href="{{ asset($pic->link) }}" target="_blank" style="float: left;"><img src="{{ asset($pic->link) }}"  width="100" height="100"></a>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if($annex)
                    <div class="layui-form-item">
                        <label class="layui-form-label">附件:</label>
                        <div>
                            @foreach($annex as $nex)
                                <a href="{{ asset($nex->link) }}" target="_blank" style="float: left;"><img src="{{ asset('images/wenjian.jpg') }}"  width="100" height="100" title="{{ $nex->link }}"></a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
            @endif

            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">备注:</label>
                <div class="layui-input-block">
                    <textarea class="layui-textarea" disabled>{{ $list->remarks }}</textarea>
                </div>
            </div>

            @if($show && !in_array($list->jsverify, [1,3,4]) && $roles == 4)
                <ul class="layui-tab-title">
                    <li class="layui-this">审核</li>
                </ul><br/>
                <form class="layui-form valideform layui-form-pane" method="POST" action="{{ route('admin.orders.verify') }}">
                    {{ csrf_field() }}
                    <div class="layui-form-item">
                        <label class="layui-form-label">是否通过:</label>
                        <div class="layui-input-inline">
                            <select id="jsverify" name="jsverify"> 
                                <option value="0" @if($list->jsverify == 0) selected @endif>待审核</option>
                                <option value="1" @if($list->jsverify == 1) selected @endif>审核通过</option>
                                <option value="2" @if($list->jsverify == 2) selected @endif>审核未通过</option>
                                <option value="3" @if($list->jsverify == 3) selected @endif>退回</option>
                            </select>
                        </div>
                        <div class="layui-form-mid layui-word-aux">
                            *流转错误的订单，可选择“退回”，消除这条订单
                        </div>
                    </div>
                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">审核备注:</label>
                        <div class="layui-input-block">
                            <textarea class="layui-textarea" name="content">{{ $list->js_content }}</textarea>
                        </div>
                    </div>  
                    <input type="hidden" name="id" value="{{$list->id}}">
                    <input type="submit" class="layui-btn float" value="提交" style="float: left;">
                </form>
            @endif

            @if($show && !in_array($list->waverify, [1,3,4]) && $roles == 3)
                <ul class="layui-tab-title">
                    <li class="layui-this">审核</li>
                </ul><br/>
                <form class="layui-form valideform layui-form-pane" method="POST" action="{{ route('admin.orders.verify') }}">
                    {{ csrf_field() }}
                    <div class="layui-form-item">
                        <label class="layui-form-label">是否通过:</label>
                        <div class="layui-input-inline">
                            <select id="waverify" name="waverify"> 
                                <option value="0" @if($list->waverify == 0) selected @endif>待审核</option>
                                <option value="1" @if($list->waverify == 1) selected @endif>审核通过</option>
                                <option value="2" @if($list->waverify == 2) selected @endif>审核未通过</option>
                                <option value="3" @if($list->waverify == 3) selected @endif>退回</option>
                            </select>
                        </div>
                        <div class="layui-form-mid layui-word-aux">
                            *流转错误的订单，可选择“退回”，消除这条订单
                        </div>
                    </div>
                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">审核备注:</label>
                        <div class="layui-input-block">
                            <textarea class="layui-textarea" name="content">{{ $list->wa_content }}</textarea>
                        </div>
                    </div>  
                    <input type="hidden" name="id" value="{{$list->id}}">
                    <input type="submit" class="layui-btn float" value="提交" style="float: left;">
                </form>
            @endif
        </div>

        <div class="layui-tab-item">
            <div class="layui-form-item">
                <label class="layui-form-label">公司名称:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $customer[$list->customer_id]['company'] }}" disabled>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">公司地址:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $customer[$list->customer_id]['address'] }}" disabled>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">公司座机:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $customer[$list->customer_id]['tel'] }}" disabled>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">用户名:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $customer[$list->customer_id]['username'] }}" disabled>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">密码:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $customer[$list->customer_id]['password'] }}" disabled>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">联系人:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $customer[$list->customer_id]['name'] }}" disabled>
                </div>
            </div>
            
            <div class="layui-form-item">
                <label class="layui-form-label">联系人电话:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $customer[$list->customer_id]['phone'] }}" disabled>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">email:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $customer[$list->customer_id]['emil'] }}" disabled>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">职位:</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" value="{{ $customer[$list->customer_id]['job'] }}" disabled>
                </div>
            </div>

            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">备注:</label>
                <div class="layui-input-block">
                    <textarea class="layui-textarea" disabled>{{ $customer[$list->customer_id]['remarks'] }}</textarea>
                </div>
            </div>
        </div>

        <div class="layui-tab-item">
            @foreach($distribute as $value)
             <ul class="layui-timeline">
                <li class="layui-timeline-item">
                    <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                    <div class="layui-timeline-content layui-text">
                        <h3 class="layui-timeline-title">{{ $value->updated_at }}</h3>
                        <p> 
                            {{ $value->content }}@if($value->remarks) ({{ $value->remarks }}) @endif
                        </p>
                        <p>操作人:{{ $user[$value->user_id]['nickname'] ? $user[$value->user_id]['nickname'] : $user[$value->user_id]['name'] }}</p>
                    </div>
                </li>
            </ul>
            @endforeach
        </div>
    </div>
</div>
@stop
@section('javascript')
<script>
layui.use(['element','laydate'], function(){
    var element = layui.element;
    var laydate = layui.laydate;

    @if($stagesarr)
        @foreach($stagesarr as $v)
        //执行一个laydate实例
        laydate.render({
            elem: '#billing_time{{ $loop->iteration }}',
            type: 'datetime',
            trigger: 'click'
        });
        //执行一个laydate实例
        laydate.render({
            elem: '#collection_time{{ $loop->iteration }}',
            type: 'datetime',
            trigger: 'click'
        });
        @endforeach
    @endif
});
</script>
@stop