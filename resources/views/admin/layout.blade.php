<!doctype html>
<html  class="x-admin-sm">
<head>
	<meta charset="UTF-8">
	<title>@yield('title', config('app.name')) - {{ config('app.name') }}</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/xadmin.css') }}">
    @yield('css')
    <!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="@yield('body_class')">
	
	@yield('content')

<script type="text/javascript">
    var userId = {{ auth('admin')->user()->id }};
</script>
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('lib/layui/layui.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/validform.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/xadmin.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/custom.js') }}" charset="utf-8"></script>
<script type="text/javascript">
$(function(){
	var layer;
	layui.use(['layer'], function(){
      layer = layui.layer;
    });
	$(".valideform").Validform({
        tiptype: function(msg,o,cssctl){
            if(o.type == '3'){
                layer.msg(msg);
            }
        },
        tipSweep: true,
        // postonce: true, //二次提交防御
        ajaxPost: true,
        beforeSubmit: function(curform){},
        callback: function(result){
            if(result.code > 0){
                layer.msg(result.msg);
                return false;
            }

            if(result.code == 0){
                xadmin.close();
            }
            if(result.data.uri != undefined){
                if(result.data.uri){
                    xadmin.add_tab(result.data.tabTitle, result.data.uri, 1, 2);
                    // parent.location.href = result.data.uri;
                }
                else{
                    parent.location.reload();
                }
            }

        }
    });
})
</script>

@yield('javascript')
</body>
</html>