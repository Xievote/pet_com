<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use app\index\model\User as UM;

class UserController extends Controller
{
    // 个人信息页面
    public function profile()
    {
        // 检查登录状态
        if (!session('user_id')) {
            $this->redirect('index/Login/login');
        }
        
        // 获取用户信息
        $user = UM::find(session('user_id'));
        if (!$user) {
            $this->error('用户不存在');
        }
        
        $this->assign('user', $user);
        return $this->fetch('pet/profile');
    }
    
    // 更新个人信息
    public function updateProfile(Request $request)
    {
        // 检查登录状态
        if (!session('user_id')) {
            $this->redirect('login');
        }
        
        // 获取用户信息
        $user = UM::find(session('user_id'));
        if (!$user) {
            $this->error('用户不存在');
        }
        
        // 验证数据
        $data = $request->only(['username']);
        $validate = $this->validate($data, [
            'username|用户名' => 'require|max:50',
        ]);
        
        if ($validate !== true) {
            $this->error($validate);
        }
        
        // 更新用户信息
        $user->username = $data['username'];
        $result = $user->save();
        
        if ($result) {
            // 更新 session 中的用户名
            session('username', $data['username']);
            $this->success('个人信息更新成功', 'pet/profile');
        } else {
            $this->error('个人信息更新失败');
        }
    }
}
