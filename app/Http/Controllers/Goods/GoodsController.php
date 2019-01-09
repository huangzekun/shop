<?php

namespace App\Http\Controllers\Goods;
use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class GoodsController extends Controller{
    public function index(){
        $goods=GoodsModel::all();
        $data=[
            'title'=>'商品页表',
            'list'=>$goods
        ];
        return view('goods/goods',$data);
    }


}