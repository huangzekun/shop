@extends('layouts.bst')

@section('content')
    <form method="POST" action="/dabiaoqian" >
        {{csrf_field()}}
    <table border="1">
        <tr>
            <td>标签</td>
            <td>id</td>
            <td>姓名</td>
            <td>性别</td>
            <td>添加时间</td>
            <td colspan="2">操作</td>
        </tr>
    @foreach($list as $k=>$v)
        <tr>
            <td><input type="checkbox" name="openid" value="{{$v['openid']}}"></td>
            <td width="50">{{$v['id']}}</td>
            <td><img src=" {{$v['headimgurl']}}"></td>
            <td>{{$v['nickname']}}</td>
            <td><?php echo date('Y-m-d H:i:s',$v['add_time'])?></td>
            <td><a>加入黑名单</a></td>
        </tr>
        @endforeach
    </table>
        <input type="submit">
    {{$list->links()}}
    </form>
@endsection
@section('footer')
    <script>

    </script>
@endsection