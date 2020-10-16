@extends('admin.layout')

@section('title', '系统设置')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form action="{{ route('admin.system.settings') }}" method="POST" class="layui-form layui-form-pane valideform">
            {{ csrf_field() }}
            <div class="layui-form-item">
                <label for="cycle" class="layui-form-label">
                    回收周期
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="cycle" name="cycle" autocomplete="off" class="layui-input" placeholder="天" datatype="n" value="{{ isset($config['cycle']) ? $config['cycle'] : '' }}">
                </div>
                <div class="layui-form-mid layui-word-aux">周期内未产生订单，则回收到客户公海</div>
            </div>
            <div class="layui-form-item">
                <label for="sk_remind_time" class="layui-form-label">
                    收款提醒
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="sk_remind_time" name="sk_remind_time" autocomplete="off" class="layui-input" placeholder="天" datatype="n" value="{{ isset($config['sk_remind_time']) ? $config['sk_remind_time'] : '' }}">
                </div>
                <div class="layui-form-mid layui-word-aux">开票后周期内未收款，则提醒销售</div>
            </div>
            <!-- <div class="layui-form-item">
                <label for="ht_remind_time" class="layui-form-label">
                    合同提醒
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="ht_remind_time" name="ht_remind_time" autocomplete="off" class="layui-input" placeholder="天" datatype="n" value="{{ isset($config['ht_remind_time']) ? $config['ht_remind_time'] : '' }}">
                </div>
                <div class="layui-form-mid layui-word-aux">订单创建后周期内没有合同，则提醒财务人员</div>
            </div> -->
            <div class="layui-form-item">
                <div class="layui-input-block">
                  <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop