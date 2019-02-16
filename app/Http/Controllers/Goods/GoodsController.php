<?php

namespace App\Http\Controllers\Goods;
use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Laravel\Scout\Searchable;

class GoodsController extends Controller{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
       // $goods=GoodsModel::all();
        $links=GoodsModel::paginate(5);
        $data=[
            'title'=>'商品页表',
            'list'=>$links,
        ];
        return view('goods/goods',$data);
    }


    public function pay(){
        $links=GoodsModel::paginate(4);
        $value='';
        foreach($links as $k=>$v){
            $value.=$v['goods_name'];
        }
        $data=[
            'title'=>'分页搜索',
            'list'=>$links
        ];
        $key="fenye";
        $expired_at=20;
        Redis::setex($key,$expired_at,$value);

        return view('goods/pay',$data);
    }

    public function payadd(){
        $sousuo=$_POST['sousuo'];
        $links=GoodsModel::where('goods_name','like',"%$sousuo%")->paginate(4);
        $value="";
        foreach($links as $k=>$v){
            $value.=$v['goods_name'];
        }
        $key="suosouhou";
        $expired_at=15;
        Redis::setex($key,$expired_at,$value);
        $data=[
            'title'=>'分页搜索',
            'list'=>$links
        ];

        return view('goods/pay',$data);
    }

}