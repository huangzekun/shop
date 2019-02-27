@extends('layouts.bst')

@section('title') {{$title}}    @endsection

@section('content')
    <form method="post" action="/loginadd">
        {{csrf_field()}}
        <table class="table table-condensed" >
            <tr align="center">
                <td class="active"><b>订单号</b></td>
                <td class="active"><b>订单价格</b></td>
                <td class="active"><b>订单时间</b></td>
            </tr>
            <!-- On cells (`td` or `th`) -->
                <tr align="center">
                    <td class="success">{{$list['order_sn']}}</td>
                    <td class="success">{{$list['order_amout']}}</td>
                    <td class="success">{{$list['add_time']}}</td>
                </tr>
        </table>
        <a href="/pay/alipay/pay/{{$list['order_id']}}" class="btn btn-danger">支付宝付款 </a>
        <a href="/weixin/pay/test/{{$list['order_id']}}" class="btn btn-success">微信付款 </a>
    </form>
@endsection