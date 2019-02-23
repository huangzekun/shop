@extends('layouts.bst')

@section('title')    @endsection

@section('header')
    @parent
@endsection

@section('content')
    <h1>客服聊天:<i style="color:red">{{$res['nickname']}}</i></h1>
    <div style="border:6px #00a7d0 solid; width: 600px;" id="chat_div"></div>
    <br>
    <form action="">
        <input type="hidden" value="{{$res['openid']}}" id="openid">
        <input type="hidden" value="1" id="msg_pos">                <!--上次聊天位置-->
        <input type="text" id="send_msg">
        <input type="submit" value="发送" id="send_msg_btn">
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
                    if(d.errno==0){     //服务器响应正常
                        //数据填充
                        //数据填充
                        if(d.data.msg_type==1){
                            var msg_str = '<blockquote>' + d.data.add_time +
                                    '<p>' + d.data.msg + '</p>' +
                                    '</blockquote>';
                        }else{
                            var msg_str = "<blockquote style='height:70px;'><font style='float: right;clear:both;'>" + d.data.add_time +
                                    '</font><p style="float: right;clear:both;">' + d.data.msg + '</p>' +
                                    '</blockquote>';
                        }
                        $("#chat_div").append(msg_str);
                        $("#msg_pos").val(d.data.id)
                    }else{

                    }
                }
            })
        },2000)

        // 客服发送消息 begin


        //客服发送消息
        $('#send_msg_btn').click(function (e) {
            e.preventDefault();
            var send_msg = $('#send_msg').val().trim();
            console.log(send_msg);
            //console.log(message);
            //$("#chat_div").append(msg_str);
            $('#send_msg').val('');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/chat/msg',
                type: 'post',
                data: {openid: openid, msg: send_msg},
                dataType: 'json',
                success: function (a) {
                    if (a.errcode == 0) {
                        alert('发送成功');
                    } else {
                        alert('发送失败');
                    }
                }
            })
        })
    </script>
@endsection
