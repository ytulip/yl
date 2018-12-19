<!DOCTYPE HTML>
<html>
<head>
    <title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <!-- Bootstrap Core CSS -->
    <link href="/admin/css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    <!-- Custom CSS -->
    <link href="/admin/css/style.css" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="/admin/css/morris.css" type="text/css"/>
    <!-- Graph CSS -->
    <link href="/admin/css/font-awesome.css" rel="stylesheet">
    <link href="/admin/css/admin.css" rel="stylesheet">
    <link href="/js/plugin/dateinput/bootstrap-datetimepicker.css" rel="stylesheet"/>
    <!-- jQuery -->
    <script src="/admin/js/jquery-2.1.4.min.js"></script>
    <!-- //jQuery -->
    <!-- lined-icons -->
    <link rel="stylesheet" href="/admin/css/icon-font.min.css" type='text/css' />
    <!-- //lined-icons -->
    <style>

        #menu-academico-sub{display: none;}
        .menu-fa{display: none;}
        .sidebar-collapsed .menu-fa{display: inline-block;}
        .sidebar-collapsed #menu-academico-sub{display: inline-block;}
        .sidebar-collapsed .sub-menu{display: none;}

        .sub-menu{padding-left: 40px;}

        #admin_message_wrap #admin_message_panel{display: none;}
        #admin_message_wrap:hover #admin_message_panel{display: block;}


        #admin_user_panel {
            display: none;
            list-style: none;
            padding: 0;
            margin: 0;
            width: 110px;
            text-align: center;
            background-color: #ffffff;
        }

        #admin_user_panel li {
            display: block;
            border-bottom: 1px solid #e9e9ea;
        }

        #admin_user_wrap:hover #admin_user_panel{display: block;}
    </style>
    @section('style')
    @show
</head>
<body>
<div>
    @section('segment_content')

        @show
</div>
<script>
    var toggle = true;

    $(".sidebar-icon").click(function() {
        if (toggle)
        {
            $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
            $("#menu span").css({"position":"absolute"});
        }
        else
        {
            $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
            setTimeout(function() {
                $("#menu span").css({"position":"relative"});
            }, 400);
        }

        toggle = !toggle;
    });
</script>
<!--js -->
<script src="/admin/js/jquery.nicescroll.js"></script>
<script src="/admin/js/scripts.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="/admin/js/bootstrap.min.js"></script>
<!-- /Bootstrap Core JavaScript -->
<!-- morris JavaScript -->
<script src="/admin/js/jquery.base64.js"></script>
<script src="/admin/js/raphael-min.js"></script>
<script src="/admin/js/morris.js"></script>
<script src="/admin/js/echarts.simple.min.js?v={{env('VERSION')}}"></script>
<script src="/admin/js/layer/layer/layer.js"></script>
<script src="/admin/js/common.js"></script>
<script>
    jQuery.browser={};(function(){jQuery.browser.msie=false; jQuery.browser.version=0;if(navigator.userAgent.match(/MSIE ([0-9]+)./)){ jQuery.browser.msie=true;jQuery.browser.version=RegExp.$1;}})();
</script>
<script src="/js/plugin/dateinput/bootstrap-datetimepicker.js"></script>
<script src="/js/plugin/dateinput/bootstrap-datetimepicker.zh-CN.js"></script>
<script>
    $('.form_date').datetimepicker({
        language:  'zh-CN',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

    function goHref(href)
    {
        location.href = href;
    }
</script>
@section('script')
@show
</body>
</html>