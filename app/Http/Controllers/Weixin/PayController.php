<?php

namespace App\Http\Controllers\Weixin;

use App\Model\OrderModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Weixin\WXBizDataCryptController;

class PayController extends Controller{
    public $weixin_unifiedorder_url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    public $weixin_notify_url = 'https://www.anjingdehua.cn/weixin/pay/notice';     //支付通知回调

    public function test($order_id){
        $total_fee=1;   //支付总金额
        //$order_id=OrderModel::generateOrderSN();
        //print_r($order_id);

        $orderInfo=OrderModel::where(['order_id'=>$order_id])->first();
        $order_info = [
            'appid'         =>  env('WEIXIN_APPID_0'),      //微信支付绑定的服务号的APPID
            'mch_id'        =>  env('WEIXIN_MCH_ID'),       // 商户ID
            'nonce_str'     => str_random(16),             // 随机字符串
            'sign_type'     => 'MD5',
            'body'          => '测试订单-'.mt_rand(1111,9999) . str_random(6),
            'out_trade_no'  => $orderInfo->order_sn,                       //本地订单号
            'total_fee'     => $total_fee,
            'spbill_create_ip'  => $_SERVER['REMOTE_ADDR'],     //客户端IP
            'notify_url'    => $this->weixin_notify_url,        //通知回调地址
            'trade_type'    => 'NATIVE'                         // 交易类型
        ];
        $this->values=[];
        $this->values=$order_info;
        $this->SetSign();
        $xml = $this->ToXml();      //将数组转换为XML
        $rs = $this->postXmlCurl($xml, $this->weixin_unifiedorder_url, $useCert = false, $second = 30);
        $data =  simplexml_load_string($rs);
        $info = [
            'code_url'=>$data->code_url,
            'order_id'=>$order_id
        ];
        return view('weixin.qrcode',$info);

    }

    public function SetSign(){
        $sign=$this->MakeSign();
        $this->values['sign'] = $sign;
        return $sign;
    }

    public function MakeSign(){
        //签名步骤一：按字典序排序参数
        ksort($this->values);
        $string = $this->ToUrlParams();
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".env('WEIXIN_MCH_KEY');
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }
    /**
     * 格式化参数格式化成url参数
     */
    protected function ToUrlParams()
    {
        $buff = "";
        foreach ($this->values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    protected function ToXml()
    {
        if(!is_array($this->values)
            || count($this->values) <= 0)
        {
            die("数组数据异常！");
        }
        $xml = "<xml>";
        //print_r($this->values);exit;
        foreach ($this->values as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    private  function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//		if($useCert == true){
//			//设置证书
//			//使用证书：cert 与 key 分别属于两个.pem文件
//			curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
//			curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
//			curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
//			curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
//		}
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            die("curl出错，错误码:$error");
        }
    }


    /**
     * 微信支付回调
     */
    public function notice(){
        $data = file_get_contents("php://input");

        //记录日志
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        //file_put_contents('logs/wx_pay_notice.log',$log_str,FILE_APPEND);

        $xml = simplexml_load_string($data);

        if($xml->result_code=='SUCCESS' && $xml->return_code=='SUCCESS'){      //微信支付成功回调
            //验证签名
            $sign=$this->Sign($data);

            if($sign==$xml->sign){       //签名验证成功
                //TODO 逻辑处理  订单状态更新

                OrderModel::where(['order_sn'=>$xml->out_trade_no])->update(['pay_time'=>time(),'is_pay'=>1,'plat'=>2]);
            }else{
                //TODO 验签失败
                echo '验签失败，IP: '.$_SERVER['REMOTE_ADDR'];
                // TODO 记录日志
            }

        }

        $response = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        echo $response;

    }

    public function Sign($data){
        $data = json_decode(json_encode(simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        $this->values = [];
        $this->values = $data;
        $sign=$this->SetSign();
        return $sign;

    }

    public function success(Request $resquest){
        $order_id=$resquest->input('order_id');
        $res=OrderModel::where(['order_id'=>$order_id])->first();
        if($res->is_pay== 1){
            $data=[
                'error'=>0,
                'msg'=>"支付成功"
            ];
        }else{
            $data=[
                'error'=>4001,
                'msg'=>"支付失败"
            ];
        }
        return $data;
    }

    public function wxlogin(){
        $myurl="http://mall.77sc.com.cn/weixin.php?r1=https://www.anjingdehua.cn/weixin/wxlogin2";
        $data = [
            "url"=>'https://open.weixin.qq.com/connect/qrconnect?appid=wxe24f70961302b5a5&redirect_uri='.urlencode($myurl).'&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect'
        ];
        return view("weixin.wxlogin",$data);

        //https://open.weixin.qq.com/connect/qrconnect?appid=wxe24f70961302b5a5&redirect_uri=http%3A%2F%2Fmall.77sc.com.cn%2Fweixin.php&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect
    }
    public function wxlogin2(){
        $code = $_GET['code'];
        $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxe24f70961302b5a5&secret=0f121743ff20a3a454e4a12aeecef4be&code='.$code.'&grant_type=authorization_code';
        $token_json = file_get_contents($token_url);
        $token_arr = json_decode($token_json,true);
        echo '<hr>';
        echo '<pre>';print_r($token_arr);echo '</pre>';

        $access_token = $token_arr['access_token'];
        $openid = $token_arr['openid'];

        // 3 携带token  获取用户信息
        $user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $user_json = file_get_contents($user_info_url);

        $user_arr = json_decode($user_json,true);
        echo '<hr>';
        echo '<pre>';print_r($user_arr);echo '</pre>';
    }

}