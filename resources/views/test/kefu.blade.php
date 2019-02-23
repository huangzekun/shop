@extends('layouts.bst')

@section('title')    @endsection

@section('header')
    @parent
@endsection

@section('content')
    <h1>客服聊天:<i style="color:red">{{$res['nickname']}}</i></h1>
    <div style="border:6px #00a7d0 solid; width: 600px; height: 500px;"></div>
    <br>
    <form action="">
        <input type="hidden" value="{{$res['openid']}}" id="openid">
        <input type="hidden" value="1" id="msg_pos">                <!--上次聊天位置-->
        <input type="text">
        <input type="submit" value="发送">
    </form>
@endsection

@section('footer')
    @parent
    <script>
        var openid = $("#openid").val();
        //console.log(openid);
        setInterval(function(){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:'/kefu/chat?openid=' + openid + '&pos=' + $("#msg_pos").val(),
                type:'get',
                dataType:'json',
                success:function(d){

                }
            })
        },5000)
    </script>
@endsection
