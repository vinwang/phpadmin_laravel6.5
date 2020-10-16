<!doctype html>
<html  class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>登录 - {{ config('app.name') }}</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/xadmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body class="login-bg">
  
    <div class="login layui-anim layui-anim-up">
        <div class="message">{{ config('app.name') }}-管理登录</div>
        <div id="darkbannerwrap"></div>
        
        <form method="post" class="layui-form valideform" action="{{ route('admin.postLogin') }}">
            {{ csrf_field() }}
            <div class="layui-form-item">
                <input name="name" placeholder="用户名"  type="text" datatype="*" class="layui-input" >
            </div>
            <div class="layui-form-item">
                <input name="password" datatype="*" placeholder="密码"  type="password" class="layui-input">
            </div>
            <div class="layui-form-item">
                <div class="layui-row">
                    <div class="layui-col-xs7">
                        <input name="captcha" placeholder="验证码"  type="text" datatype="*" class="layui-input">
                    </div>
                    <div class="layui-col-xs5">
                        <div style="margin-left: 10px;">
                          <img src="{{ captcha_src('math') }}" onclick="this.src='{{ captcha_src('math') }}&t='+ Math.random()">
                        </div>
                    </div>
                </div>
            </div>
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20" >
        </form>
        @if($errors->any())
            @foreach($errors->all() as $error)
                <p class="layui-text" style="color:#FF5722">{{ $error }}</p>
            @endforeach
        @endif
    </div>
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('lib/layui/layui.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/validform.js') }}" charset="utf-8"></script>
<script type="text/javascript">
$(function(){
    var layer;
    layui.use('layer', function(){
      layer = layui.layer;
    });
    $(".valideform").Validform({
        tiptype: function(msg,o,cssctl){
            if(o.type == '3'){
                layer.msg(msg);
            }
        },
        tipSweep: true
    });
})
</script>
</body>
</html>