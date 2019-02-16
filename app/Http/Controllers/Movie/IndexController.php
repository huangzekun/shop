<?php

namespace App\Http\Controllers\Movie;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller{
    public function index(){

        $key="best_box";
        $info=[];
        for($i=0;$i<30;$i++){
            $arr=Redis::getbit($key,$i);
            $info[$i]=$arr;
        }
        $data=[
            'seat'=>$info
        ];
        return view('movie.index',$data);
    }

    public function buy($pos,$status){
        $key="best_box";
        Redis::setbit($key,$pos,1);
        header('refresh:2,/movie');
        echo '牛皮，你抢到了';
    }
}
