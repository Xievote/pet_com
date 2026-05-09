<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\User;
use think\Request;
use think\Cache;
use app\common\SuperAdmin;

class Login extends Controller
{
    public function register()
    {
        try {
            return $this->fetch('pet/register');
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage() . '\n' . $e->getTraceAsString();
        }
    }

    public function doRegister(Request $request)
    {
        $data = $request->post();
        if (strlen($data['password']) < 6) {
            return $this->error('密码太短了');
        }

        $user = new User;
        $user->username = $data['username'];
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->create_time = time();

        if ($user->save()) {
            return $this->success('注册成功，去登录吧！', 'login');
        }
        return $this->error('注册失败，可能用户名已存在');
    }

    public function login()
    {
        try {
            $token = bin2hex(random_bytes(16));
            session('login_csrf', $token);
            $this->assign('csrf_token', $token);
            return $this->fetch('pet/login');
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage() . '\n' . $e->getTraceAsString();
        }
    }

    public function doLogin(Request $request)
    {
        $data = $request->post();
        $postedCsrf = isset($data['csrf_token']) ? $data['csrf_token'] : '';
        if ($postedCsrf === '' || $postedCsrf !== session('login_csrf')) {
            return $this->error('请求无效或已过期，请重新打开登录页');
        }
        session('login_csrf', null);

        $username = isset($data['username']) ? trim($data['username']) : '';
        if ($username === '' || !isset($data['password'])) {
            return $this->error('请输入用户名和密码');
        }

        $ip = $request->ip();
        $failKey = 'login_fail_' . md5($ip . ':' . $username);
        $fails = (int) Cache::get($failKey);
        if ($fails >= 5) {
            return $this->error('登录尝试过多，请 15 分钟后再试');
        }

        $user = User::where('username', $username)->find();
        if ($user && password_verify($data['password'], $user->password)) {
            Cache::rm($failKey);
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_regenerate_id(true);
            }
            session('user_id', $user->id);
            session('username', $user->username);
            $isSuper = SuperAdmin::verifyFromDb($user->id);
            session('is_super_admin', $isSuper ? 1 : 0);

            $target = $isSuper ? '/admin' : '/index';
            return $this->success('登录成功', $target);
        }

        Cache::set($failKey, $fails + 1, 900);
        return $this->error('用户名或密码错误');
    }

    public function logout()
    {
        session(null);
        return $this->success('已退出', 'login');
    }
}
