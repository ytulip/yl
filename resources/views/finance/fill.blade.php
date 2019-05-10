@extends('finance.master',['headerTitle'=>'金融讲堂'])
@section('style')
    <style>
        .m-t-16{margin-top: 16px;}
        .paginate-list-row {
            padding-top: 0;
            font-size: 14px;
            border: 1px solid #EAEEF7;
            background-color: #ffffff;
            height: 35px;
        }

        .paginate-list-row div{
            border-right: 1px solid #EAEEF7;
            background-color: #ffffff;
            height: 100%;
            line-height: 35px;
        }

    </style>
@stop
@section('left_content')
    <div>
        <iframe src="/finance-class/wenjuan?id={{\Illuminate\Support\Facades\Request::input('id')}}" frameborder="0" scrolling="no" id="test" style="width: 100%;margin-bottom: 50px;"></iframe>
    </div>

@stop

@section('script')
    <script>
        function reinitIframe(){
            var iframe = document.getElementById("test");
            try{
                iframe.height = iframe.contentWindow.document.body.clientHeight;
            }catch (ex){}
        }
        window.setInterval("reinitIframe()", 200);
    </script>
@stop