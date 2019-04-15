@extends('_layout.master')
@section('title')
    <title>详情</title>
@stop
@section('style')
    <style>
        .low-alert{position: fixed;left:0;right: 0;bottom: 90px;text-align: center;}
        .item-opr span{line-height: 40px;display: inline-block;}
        .show-img{width: 100%;border-radius: 12px;}
        .pro-essay-barr{border-bottom: 1px solid #9c9c9c;margin: 20px 0;}

        .pre-text {
            white-space: pre-wrap;
            word-wrap: break-word;
            word-break: break-all;
        }
    </style>
@stop
@section('container')
    {{--@include('segments.header',['headerTile'=>'最新动态'])--}}

    <div class="padding-container">
        <p class="pre-text">{{$product->common_ques}}</p>
    </div>
@stop