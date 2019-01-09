<?php

namespace App\Http\Controllers\Cart;

use App\Model\CartModel;
use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;

class CartController extends Controller{

    public function index(){

        $goods=CartModel::where(['uid'=>session()->get('uid')])->get()->toArray();
        if(empty($goods)){
            echo '购物车是空的';
        }
        foreach($goods as $k=>$v) {
            $info = GoodsModel::where(['goods_id' => $v['goods_id']])->first()->toArray();
            $info['num'] = $v['num'];
            $info['addtime']=$v['addtime'];
            $list[]=$info;
        }
            $data=[
                'list'=>$list
            ];
        //print_r($data);die;
        return view('Cart/cart',$data);
    }

    //商品添加
    public function cartadd($goods_id){
        $cart_goods=session()->get('cart_goods');
        if(!empty($cart_goods)){
            if(in_array($goods_id,$cart_goods)){
                echo "已存在购物车里";
                exit;
            }
        }
        session()->push('cart_goods',$goods_id);

        //减存值
        $where=[
            'goods_id'=>$goods_id
        ];
        $num=GoodsModel::where($where)->value('goods_num');
        if($num<=0){
            echo '商品数量不足';
            exit;
        }
        $res=GoodsModel::where(['goods_id'=>$goods_id])->decrement('goods_num');
        if($res){
            echo '添加成功';
        }

    }

    //删除商品
    public function cartdel($goods_id){
        $cart_goods=session()->get('cart_goods');
        if(in_array($goods_id,$cart_goods)){
            foreach($cart_goods as $k=>$v){
                if($goods_id == $v ){
                    session()->pull('cart_goods.'.$k);
                    echo "删除成功";
                }
            }
        }else{
            die('商品不在购物车中');
        }
    }


    //加入购物车
    public function addcart($goods_id){
        $good=GoodsModel::where(['goods_id'=>$goods_id])->first();
        $goods=[
            'title'=>'加入购物车',
            'goods'=>$good,
        ];
        return view('Cart.cartadd',$goods);
    }

    public function addcart2(){
        $goods_id=request()->input('goods_id');
        $num=request()->input('num');
        $goods_num=GoodsModel::where(['goods_id'=>$goods_id])->value('goods_num');
        if($goods_num<$num){
            $res=[
                'error'=>5001,
                'msg'=>'数量不足',
            ];
            return $res;
        }
        //入库cart
        $cart=CartModel::where(['uid'=>session()->get('uid'),'goods_id'=>$goods_id])->first();
        if(empty($cart)) {
            $data = [
                'goods_id' => $goods_id,
                'uid' => session()->get('uid'),
                'addtime' => time(),
                'session_token' => session()->get('u_token'),
                'num' => $num
            ];
            $cart_id = CartModel::insertGetId($data);
        }else{
            $data=[
                'addtime'=>time(),
                'num'=>$num+$cart['num'],
                'session_token'=>session()->get('u_token'),
            ];
            $cart_id=CartModel::where(['id'=>$cart['id']])->update($data);
        }
        if(!$cart_id){
            $res=[
                'error'=>5002,
                'msg'=>'添加购物车失败',
            ];
            return $res;
        }
        if($cart_id){
            $res=[
                'error'=>0,
                'msg'=>'购物车添加成功',
            ];
            //减存值
            GoodsModel::where(['goods_id'=>$goods_id])->decrement('goods_num',$num);
            return $res;
        }

    }

    //删除购物车商品
    public function del($goods_id){
        CartModel::where(['uid'=>session()->get('uid'),'goods_id'=>$goods_id])->delete();
        echo '商品ID:  '.$goods_id . ' 删除成功1';
        header('refresh:2,/cart');
    }
}
