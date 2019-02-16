<p>@extends('layouts.bst')

@section('title') {{$title}}    @endsection

@section('header')
    @parent
@endsection

@section('content')
    <form method="post" action="/payadd">
        {{csrf_field()}}
        <table class="table table-condensed" >
            <input type="text" name="sousuo">
            <input type="submit" value="搜索">
            <tr align="center">
                <td class="active"><b>商品名称</b></td>
                <td class="success"><b>商品价格</b></td>
                <td class="warning"><b>商品数量</b></td>
                <td class="danger"><b>商品积分</b></td>
            </tr>
            <!-- On cells (`td` or `th`) -->
            @foreach($list as $k=>$v)
                <tr align="center">
                    <td class="active">{{$v['goods_name']}}</td>
                    <td class="success">{{$v['goods_price']}}</td>
                    <td class="warning">{{$v['goods_num']}}</td>
                    <td class="danger">...</td>
                </tr>
            @endforeach
        </table>
    </form>
    {{$list->links()}}
@endsection

@section('footer')
@endsection