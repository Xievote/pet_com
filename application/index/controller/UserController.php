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
            return json(['code' => 401, 'msg' => '请先登录']);
        }
        
        // 获取用户信息
        $user = UM::find(session('user_id'));
        if (!$user) {
            return json(['code' => 404, 'msg' => '用户不存在']);
        }
        
        // 验证数据
        $data = $request->only(['username', 'bio', 'gender', 'birthday', 'hometown', 'zodiac', 'mbti']);
        
        // 验证用户名
        if (empty($data['username'])) {
            return json(['code' => 400, 'msg' => '用户名不能为空']);
        }
        if (strlen($data['username']) > 50) {
            return json(['code' => 400, 'msg' => '用户名不能超过50个字符']);
        }
        
        // 验证个性描述长度
        if (!empty($data['bio']) && strlen($data['bio']) > 500) {
            return json(['code' => 400, 'msg' => '个性描述不能超过500个字符']);
        }
        
        // 验证故乡长度
        if (!empty($data['hometown']) && strlen($data['hometown']) > 50) {
            return json(['code' => 400, 'msg' => '故乡不能超过50个字符']);
        }
        
        // 处理头像上传
        $avatarFile = $request->file('avatar_file');
        if ($avatarFile) {
            // 验证图片格式和大小
            $info = $avatarFile->validate([
                'size' => 5242880, // 5MB
                'ext' => 'jpg,png,jpeg'
            ])->move('uploads/avatars');
            
            if ($info) {
                // 删除旧头像（如果存在）
                if ($user->avatar && file_exists('.' . $user->avatar)) {
                    @unlink('.' . $user->avatar);
                }
                $user->avatar = '/uploads/avatars/' . $info->getSaveName();
            } else {
                return json(['code' => 400, 'msg' => '头像上传失败：' . $avatarFile->getError()]);
            }
        }
        
        // 更新用户信息
        $user->username = $data['username'];
        if (isset($data['bio'])) $user->bio = htmlspecialchars($data['bio']);
        if (isset($data['gender'])) $user->gender = $data['gender'];
        if (isset($data['birthday'])) $user->birthday = $data['birthday'];
        if (isset($data['hometown'])) $user->hometown = htmlspecialchars($data['hometown']);
        if (isset($data['zodiac'])) $user->zodiac = $data['zodiac'];
        if (isset($data['mbti'])) $user->mbti = $data['mbti'];
        
        try {
            if ($user->save()) {
                // 更新 session 中的用户名
                session('username', $data['username']);
                return json(['code' => 200, 'msg' => '个人信息更新成功']);
            } else {
                return json(['code' => 500, 'msg' => '个人信息更新失败']);
            }
        } catch (\Exception $e) {
            return json(['code' => 500, 'msg' => '更新失败：' . $e->getMessage()]);
        }
    }
    
    // 获取用户头像API（用于懒加载）
    public function getAvatar()
    {
        $userId = $this->request->param('user_id');
        if (!$userId) {
            return json(['code' => 400, 'msg' => '用户ID不能为空']);
        }
        
        $user = UM::find($userId);
        if (!$user) {
            return json(['code' => 404, 'msg' => '用户不存在']);
        }
        
        return json([
            'code' => 200,
            'data' => [
                'avatar' => $user->avatar,
                'username' => $user->username
            ]
        ]);
    }
}
