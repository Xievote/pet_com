<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\User;
use think\Request;

class Login extends Controller
{
    // 注册页面
    public function register()
    {
        try {
            return $this->fetch('pet/register');
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage() . '\n' . $e->getTraceAsString();
        }
    }

    // 处理注册提交
    public function doRegister(Request $request)
    {
        $data = $request->post();
        // 简单验证
        if(strlen($data['password']) < 6) return $this->error('密码太短了');

        $user = new User;
        $user->username = $data['username'];
        // 使用 TP5 内置的 password_hash 加密
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT); 
        $user->create_time = time();

        if($user->save()){
            return $this->success('注册成功，去登录吧！', 'login');
        } else {
            return $this->error('注册失败，可能用户名已存在');
        }
    }

    // 登录页面
    public function login()
    {
        try {
            return $this->fetch('pet/login');
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage() . '\n' . $e->getTraceAsString();
        }
    }

    // 处理登录
    public function doLogin(Request $request)
    {
        $data = $request->post();
        $user = User::where('username', $data['username'])->find();

        if($user && password_verify($data['password'], $user->password)){
            // 登录成功，写入 Session
            session('user_id', $user->id);
            session('username', $user->username);
            return $this->success('登录成功', '/index'); // 跳转回宠物主页
        } else {
            return $this->error('用户名或密码错误');
        }
    }
    
    // 退出登录
    public function logout()
    {
        session(null);
        return $this->success('已退出', 'login');
    }
}