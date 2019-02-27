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
    </script>
@endsection
