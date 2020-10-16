@extends('admin.layout')

@section('title', '业务终止')

@section('body_class', 'index')

@section('content')
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-form-pane valideform" method="POST" action="{{ route('admin.orders.jscost', $id) }}">
                        {{ csrf_field() }}
                        <div class="layui-form-item">
                            <label for="equipment_cost" class="layui-form-label">设备成本</label>
                            <div class="layui-input-inline">
                                <input type="number" id="equipment_cost" name="equipment_cost" autocomplete="off" class="layui-input" value="{{ $cost ? $cost->equipment_cost : '' }}" datatype="n" ignore="ignore">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label for="trusteeship_cost" class="layui-form-label">托管费用</label>
                            <div class="layui-input-inline">
                                <input type="number" id="trusteeship_cost" name="trusteeship_cost" autocomplete="off" class="layui-input" value="{{ $cost ? $cost->trusteeship_cost : '' }}" datatype="n" ignore="ignore">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label for="trusteeship_cost" class="layui-form-label">软件成本</label>
                            <div class="layui-input-inline">
                                <input type="number" id="software_cost" name="software_cost" autocomplete="off" class="layui-input" value="{{ $cost ? $cost->software_cost : '' }}" datatype="n" ignore="ignore">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label for="jishu_other_cost" class="layui-form-label">其他成本</label>
                            <div class="layui-input-inline">
                                <input type="number" id="jishu_other_cost" name="jishu_other_cost" autocomplete="off" class="layui-input" value="{{ $cost ? $cost->jishu_other_cost : '' }}" datatype="n" ignore="ignore">
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label for="jishu_remarks" class="layui-form-label">备注</label>
                            <div class="layui-input-block">
                                <textarea placeholder="" id="jishu_remarks" name="jishu_remarks" class="layui-textarea">{{ $cost ? $cost->jishu_remarks : ''}}</textarea>
                            </div>
                        </div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-filter="add" lay-submit="">提交</button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div> 
@stop