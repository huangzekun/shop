<p>@extends('layouts.bst')

@section('title') {{$title}}    @endsection

@section('header')
    @parent
@endsection

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
            @foreach($list as $k=>$v)
            <tr align="center">
                <td class="success">{{$v['order_sn']}}</td>
                <td class="success">{{$v['order_amout']}}</td>
                <td class="success">{{$v['add_time']}}</td>
            </tr>
            @endforeach
        </table>

    </form>
@endsection

@section('footer')
    @parent
@endsection