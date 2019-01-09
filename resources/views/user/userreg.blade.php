<p>@extends('layouts.bst')

@section('title')     @endsection

@section('header')
@endsection

@section('content')
    <form method="post" action="/regadd">
        {{csrf_field()}}
        <div class="form-group">
            <label for="exampleInputEmail1">用户名：</label>
            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="请输入用户名" style="width: 250px">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">密码：</label>
            <input type="password" class="form-control" id="exampleInputPassword1" name="user_pwd" placeholder="请输入密码" style="width: 250px">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">手机号：</label>
            <input type="text" class="form-control" id="user_tel" name="user_tel" placeholder="请输入手机号" style="width: 250px">
        </div>
        <button type="submit" class="btn btn-default">注册</button>
    </form>
@endsection


@section('footer')
    @parent
@endsection
