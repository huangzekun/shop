<?php
namespace App\Http\Controllers\Order;

use App\Model\CartModel;
use App\Model\GoodsModel;
use App\Model\UserModel;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\OrderModel;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }
    public function add(){
        $cart_goods=CartModel::where(['uid'=>Auth::id()])->get()->toArray();
        if(empty($cart_goods)){
            die('购物车中无商品');
        }
        $order_amout=0;
        foreach($cart_goods as $k=>$v){
            $goods_info=GoodsModel::where(['goods_id'=>$v['goods_id']])->first()->toArray();
            $goods_info['num']=$v['num'];
            $list[]=$goods_info;
            //总价
            $order_amout+=$goods_info['goods_price']*$v['num'];
        }
        //生成订单
        $order_sn=OrderModel::generateOrderSN();
        $data=[
            'u_id'=>Auth::id(),
            'order_sn'=>$order_sn,
            'add_time'=>time(),
            'order_amout'=>$order_amout
        ];
        $res=OrderModel::insertGetId($data);
        if(!$res){
            die('生成失败');
        }
        $data['order_id']=$res;
        $info=[
            'title'=>"确认支付",
            'list'=>$data,
        ];
        //print_r($data);exit;

        //清空购物车
        CartModel::where(['uid'=>Auth::id()])->delete();

        return view('order/order',$info);
    }

    public function good($order_id){
        $info=OrderModel::where(['order_id'=>$order_id])->first();
        if(!$info){
            echo '订单不存在';
            exit;
        }
        if($info->pay_time >0){
            echo '订单已支付';
            exit;
        }
        //支付成功修改时间
        OrderModel::where(['order_id'=>$order_id])->update(['pay_time'=>time(),'pay_amout'=>rand(1111,9999),'is_pay'=>1]);

        //增加积分
        UserModel::where(['user_id'=>Auth::id()])->increment('user_optins',$info['order_amout']);

        header('refresh:2,/center');
        echo "付款成功，等待发货";


    }


    //我的订单
    public function myorder(){
        $info=OrderModel::where(['u_id'=>Auth::id(),'is_delete'=>0])->orderBy('order_id','desc')->get()->toArray();

        if(empty($info)){
            echo '我的订单为空';
            exit;
        }
        $data=[
            'title'=>"我的订单",
            'list'=>$info,
        ];
        return view('order/myorder',$data);
    }
}