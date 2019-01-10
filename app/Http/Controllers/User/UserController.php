<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\UserModel;

class UserController extends Controller
{
    //

	public function user($uid)
	{
		echo $uid;
	}

	public function test()
    {
        echo '<pre>';print_r($_GET);echo '</pre>';
    }

	public function add()
	{
		$data = [
			'name'      => str_random(5),
			'age'       => mt_rand(20,99),
			'email'     => str_random(6) . '@gmail.com',
			'reg_time'  => time()
		];

		$id = UserModel::insertGetId($data);
		var_dump($id);
	}

	public function test1(){
		$model=UserModel::All()->toArray();
		$data=[
			'title'=>"aaa",
			'list'=>$model
		];
		return view('test/test1',$data);
	}

	//注册
	public function reg(){
		return view('user.userreg');
	}
	public function regadd(){
		$data=($_POST);
		//print_r($data);

		if(empty($data['user_name'])){
			echo '用户名不能为空'."<br>";
			exit;
		}
		if(empty($data['user_pwd'])){
			echo '密码不能为空';
			exit;
		}
		$pwd=password_hash($data["user_pwd"],PASSWORD_BCRYPT);
		$info=[
			'user_name'=>$data['user_name'],
			'user_pwd'=>$pwd,
			'user_tel'=>$data['user_tel'],
			'register_time'=>time()
		];
		$model=UserModel::insertGetId($info);
		if($model){
			$token=substr(md5(time().mt_rand(1,99999)),10,10);
			setcookie('uid',$model,time()+86400,'/','www.shop.laravel.com',false,true);
			setcookie('token',$token,time()+86400,'/center','',false,true);
			request()->session()->put('uid',$model);
			request()->session()->put('u_token',$token);
			header('refresh:2,/center');
			echo '成功';
		}else{
			header('refresh:2,/reg');
			echo '失败';
		}
	}

	//登陆
	public function login(){
		return view('user.userlogin');
	}

	public function loginadd(){
		$data=($_POST);
		$model=UserModel::where(['user_name'=>$data['user_name']])->first();
		//echo $data['user_pwd'];
		if($model){
			$pwd=password_verify($data['user_pwd'],$model['user_pwd']);
			if($pwd==true){
				$token=substr(md5(time().mt_rand(1,99999)),10,10);
				setcookie('uid',$model['user_id'],time()+86400,'/','www.shop.laravel.com',false,true);
				setcookie('token',$token,time()+86400,'/center','',false,true);
				request()->session()->put('uid',$model['user_id']);
				request()->session()->put('u_token',$token);

				header("Refresh:2;/center");
				echo '登陆成功';
			}else{
				header('refresh:2,/login');
				echo '登录失败';
			}

		}else{
			header('refresh:2,/login');
			echo '用户名有误呢';
		}

	}

	//个人
	public function center(){
		if($_COOKIE['token'] != request()->session()->get("u_token")){
			die('非法登陆');
		}
		//echo 'u_token: '.request()->session()->get('u_token'); echo '</br>';
		//echo '<pre>';print_r($request->session()->get('u_token'));echo '</pre>';

		//echo '<pre>';print_r($_COOKIE);echo '</pre>';
		return view('user.center');
	}

}
