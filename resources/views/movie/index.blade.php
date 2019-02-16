<p>@extends('layouts.bst')

@section('title')    @endsection

@section('header')
    @parent
@endsection

@section('content')
     <h2>选座</h2>
    @foreach($seat as $k=>$v)
        @if($v==1)
            <p><a href="/movie/buy/{{$k}}/{{$v}}" class="btn btn-danger">座位{{$k+1}}</a></p>
        @else
            <p><a href="/movie/buy/{{$k}}/{{$v}}" class="btn btn-success">座位{{$k+1}}</a></p>
        @endif
    @endforeach
@endsection

@section('footer')
    @parent
@endsection