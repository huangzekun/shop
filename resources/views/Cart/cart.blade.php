{{-- 购物车 --}}
@extends('layouts.bst')

@section('content')
    <div class="container">
        <ul>
            @foreach($list as $k=>$v)
                <li>{{$v['goods_id']}}    --  {{$v['goods_name']}}  -  {{$v['goods_price']}}  -  {{$v['num']}}   --  {{date('Y-m-d H:i:s',$v['addtime'])}}
                    <a href="/del/{{$v['goods_id']}}" class="del_goods">删除</a></li>
            @endforeach

        </ul>
        <a href="/order/add" id="submit_order" class="btn btn-info btn-block">提交订单 </a>
    </div>
@endsection

@section('footer')
    @parent
@endsection