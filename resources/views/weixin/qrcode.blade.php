@extends('layouts.bst')

@section('content')
    <div id="code" align="center"></div>

@endsection

@section('footer')
    @parent
    <script src="{{URL::asset('/js/qrcode.min.js')}}"></script>
    <script>
        (function() {
                    var qrcode = new QRCode('code', {
                                text: "{{$code_url}}",
                                width: 256,
                                height: 256,
                                colorDark : '#000000',
                                colorLight : '#ffffff',
                                correctLevel : QRCode.CorrectLevel.H
                            }
                    );
                }
        )()


        setInterval(function(){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/weixin/pay/success?order_id=' + "{{$order_id}}",
                type: 'get',
                dataType: 'json',
                success: function (a) {
                    //console.log(a.error)
                    if(a.error==0){
                        alert(a.msg);
                        location.href="/order/myorder";
                    }
                }
            })
        },2000)
    </script>
@endsection
