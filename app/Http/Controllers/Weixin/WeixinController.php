<?php

namespace App\Http\Controllers\Weixin;

use App\Model\WxModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp;
use Illuminate\Support\Facades\Redis;

class WeixinController extends Controller
{
    //

    protected $redis_weixin_access_token = 'str:weixin_access_token';     //微信 access_token
    /**
     * 首次接入
     */
    public function validToken1()
    {
        echo $_GET['echostr'];
    }

    /**
     * 接收微信服务器事件推送
     */
    public function wxEvent()
    {
        $data = file_get_contents("php://input");


        //解析XML
        $xml = simplexml_load_string($data);        //将 xml字符串 转换成对象

        $event = $xml->Event;                       //事件类型
        //var_dump($xml);echo '<hr>';
        $openid = $xml->FromUserName;               //用户openid

        if(isset($xml->MsgType)){
            if($xml->MsgType=="text"){
                $msg=$xml->Content;
                $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. $msg. date('Y-m-d H:i:s') .']]></Content></xml>';
                echo $xml_response;
                exit();
            }else if($xml->MsgType=="image"){
                $access_token = $this->getWXAccessToken();
                $media_id=$xml->MediaId;
                $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$media_id;
                $data = json_decode(file_get_contents($url), true);
                return $data;
            }
        }

        if($event=='subscribe'){
            $sub_time = $xml->CreateTime;               //扫码关注时间

            echo 'openid: '.$openid;echo '</br>';
            echo '$sub_time: ' . $sub_time;

            //获取用户信息
            $user_info = $this->getUserInfo($openid);
            echo '<pre>';print_r($user_info);echo '</pre>';

            //保存用户信息
            $u = WxModel::where(['openid'=>$openid])->first();
            //var_dump($u);die;
            if($u){       //用户不存在
                echo '用户已存在';
            }else{
                $user_data = [
                    'openid'            => $openid,
                    'add_time'          => time(),
                    'nickname'          => $user_info['nickname'],
                    'sex'               => $user_info['sex'],
                    'headimgurl'        => $user_info['headimgurl'],
                    'subscribe_time'    => $sub_time,
                ];

                $id = WxModel::insertGetId($user_data);      //保存用户信息
                var_dump($id);
            }
        }else if($event=='CLICK'){
            if($xml->EventKey=='kefu01'){
                $this->kefu01($openid,$xml->ToUserName);
            }
        }
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);
    }

    public function kefu01($openid,$from)
    {
        // 文本消息
        $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$from.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. 'Hello World, 现在时间'. date('Y-m-d H:i:s') .']]></Content></xml>';
        echo $xml_response;
    }




    /**
     * 接收事件推送
     */
    public function validToken()
    {
        //$get = json_encode($_GET);
        //$str = '>>>>>' . date('Y-m-d H:i:s') .' '. $get . "<<<<<\n";
        //file_put_contents('logs/weixin.log',$str,FILE_APPEND);
        //echo $_GET['echostr'];
        $data = file_get_contents("php://input");
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);
    }

    /**
     * 获取微信AccessToken
     */
    public function getWXAccessToken()
    {
        $token=Redis::get($this->redis_weixin_access_token);
        //获取缓存
         if(!$token){        // 无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APPSECRET');
            $data = json_decode(file_get_contents($url),true);

            //记录缓存
            $token = $data['access_token'];
            Redis::set($this->redis_weixin_access_token,$token);
            Redis::setTimeout($this->redis_weixin_access_token,3600);
        }
        return $token;

    }

    /**
     * 获取用户信息
     * @param $openid
     */
    public function getUserInfo($openid)
    {
        //$openid = 'oLreB1jAnJFzV_8AGWUZlfuaoQto';
        $access_token = $this->getWXAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';

        $data = json_decode(file_get_contents($url), true);
        return $data;

//        $where=[
//            'openid'=>$data['openid']
//        ];
//        $first=WxModel::where($where)->first();
//        if($first){
//            $info=[
//                'add_time'=>time(),
//                'nickname'=>$data['nickname'],
//                'sex'=>$data['sex'],
//                'headimgurl'=>$data['headimgurl'],
//                'subscribe_time'=>$data['subscribe_time']
//            ];
//            $res=WxModel::where($where)->update($info);
//            die('修改成功');
//        }else{
//            $info=[
//                'openid'=>$data['openid'],
//                'add_time'=>time(),
//                'nickname'=>$data['nickname'],
//                'sex'=>$data['sex'],
//                'headimgurl'=>$data['headimgurl'],
//                'subscribe_time'=>$data['subscribe_time']
//            ];
//            $res=WxModel::insert($info);
//        }
//        echo "关注成功";

    }

    /**
     * 菜单
     */
    public function menu(){
        $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->getWXAccessToken();


        $client=new GuzzleHttp\Client(['base_uri'=>$url]);

        $data=[
            "button"=>[
                [
                    //"type"=>"view",
                    "name"=>"里面啥都有",
                    "sub_button"=>[
                         [
                             "type"=> "view",
                             "name"=>"百度",
                             "url"=>"https://baidu.com",

                        ],
                        [
                            "type"=> "click",
                            "name"=>"客服01",
                            "key"=>"kefu01",
                        ]
                    ]
                ]
            ]
        ];
        $r = $client->request('POST', $url, [
            'body' =>GuzzleHttp\json_encode($data,JSON_UNESCAPED_UNICODE)
        ]);

        $response_arr=json_decode($r->getBody(),true);
        print_r($response_arr);
        if($response_arr['errcode']==0){
            echo '菜单创建成功';
        }else{
            echo '菜单创建失败';
        }

    }

}
