<p>@extends('layouts.mama')

@section('title') {{$title}}    @endsection

@section('header')
    @parent
    <p style="color: red;">This is Child header.</p>
@endsection

@section('content')
    <p>这里是 Child Content.
    <table border="1">
        <thead>
        <td>id</td><td>手机</td><td>邮箱</td>
        </thead>
        <tbody>
        @foreach($list as $v)
            <tr>
                <td>{{$v['user_id']}}</td><td>{{$v['user_tel']}}</td><td>{{$v['user_email']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection


@section('footer')
    @parent
    <p style="color: red;">This is Child footer .</p>
    @endsection