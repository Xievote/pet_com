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
        if (!session('user_id')) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $user = UM::find(session('user_id'));
        if (!$user) {
            return json(['code' => 404, 'msg' => '用户不存在']);
        }

        $data = $request->only(['username', 'bio', 'gender', 'birthday', 'hometown', 'zodiac', 'mbti']);

        $username = isset($data['username']) ? trim($data['username']) : '';
        if ($username === '') {
            return json(['code' => 400, 'msg' => '用户名不能为空']);
        }
        if (strlen($username) > 50) {
            return json(['code' => 400, 'msg' => '用户名不能超过50个字符']);
        }

        $bioRaw = isset($data['bio']) ? trim((string) $data['bio']) : '';
        if ($bioRaw !== '' && strlen($bioRaw) > 500) {
            return json(['code' => 400, 'msg' => '个性描述不能超过500个字符']);
        }

        $hometownRaw = isset($data['hometown']) ? trim((string) $data['hometown']) : '';
        if ($hometownRaw !== '' && strlen($hometownRaw) > 50) {
            return json(['code' => 400, 'msg' => '故乡不能超过50个字符']);
        }

        $gender = isset($data['gender']) ? trim((string) $data['gender']) : 'secret';
        if (!in_array($gender, ['male', 'female', 'secret'], true)) {
            $gender = 'secret';
        }

        $birthdayRaw = isset($data['birthday']) ? trim((string) $data['birthday']) : '';
        $birthdayNew = $birthdayRaw === '' ? null : $birthdayRaw;

        $zodiacRaw = isset($data['zodiac']) ? trim((string) $data['zodiac']) : '';
        $zodiacNew = $zodiacRaw === '' ? '' : $zodiacRaw;

        $mbtiRaw = isset($data['mbti']) ? trim((string) $data['mbti']) : '';
        if ($mbtiRaw !== '' && strlen($mbtiRaw) > 16) {
            return json(['code' => 400, 'msg' => 'MBTI 不能超过16个字符']);
        }
        $mbtiNew = $mbtiRaw === '' ? '' : $mbtiRaw;

        $bioStored = htmlspecialchars($bioRaw, ENT_QUOTES, 'UTF-8');
        $hometownStored = $hometownRaw === '' ? '' : htmlspecialchars($hometownRaw, ENT_QUOTES, 'UTF-8');

        $avatarFile = $request->file('avatar_file');
        $pendingAvatar = false;
        if ($avatarFile) {
            if (!$avatarFile->check(['size' => 5242880, 'ext' => 'jpg,png,jpeg'])) {
                return json(['code' => 400, 'msg' => '头像上传失败：' . $avatarFile->getError()]);
            }
            $pendingAvatar = true;
        }

        $oldBirthdayStr = $this->formatBirthdayForCompare($user->birthday);

        $same = ($user->username === $username)
            && ((string) ($user->bio ?? '') === $bioStored)
            && ((string) ($user->gender ?? 'secret') === $gender)
            && ($oldBirthdayStr === ($birthdayNew === null ? '' : $birthdayNew))
            && ((string) ($user->hometown ?? '') === $hometownStored)
            && ((string) ($user->zodiac ?? '') === $zodiacNew)
            && ((string) ($user->mbti ?? '') === $mbtiNew)
            && !$pendingAvatar;

        if ($same) {
            return json([
                'code' => 200,
                'msg' => '已更新请不要重复点击',
                'duplicate_submit' => true,
            ]);
        }

        if ($pendingAvatar) {
            $info = $avatarFile->validate([
                'size' => 5242880,
                'ext' => 'jpg,png,jpeg',
            ])->move('uploads/avatars');

            if (!$info) {
                return json(['code' => 400, 'msg' => '头像上传失败：' . $avatarFile->getError()]);
            }
            if ($user->avatar && file_exists('.' . $user->avatar)) {
                @unlink('.' . $user->avatar);
            }
            $user->avatar = '/uploads/avatars/' . $info->getSaveName();
        }

        $user->username = $username;
        $user->bio = $bioStored;
        $user->gender = $gender;
        $user->birthday = $birthdayNew;
        $user->hometown = $hometownStored;
        $user->zodiac = $zodiacNew;
        $user->mbti = $mbtiNew;

        try {
            if ($user->save()) {
                session('username', $username);
                return json(['code' => 200, 'msg' => '个人信息更新成功']);
            }
            return json(['code' => 500, 'msg' => '个人信息更新失败']);
        } catch (\Exception $e) {
            return json(['code' => 500, 'msg' => '更新失败：' . $e->getMessage()]);
        }
    }

    /**
     * @param mixed $birthday
     * @return string 空字符串表示未填写
     */
    protected function formatBirthdayForCompare($birthday)
    {
        if ($birthday === null || $birthday === '') {
            return '';
        }
        if ($birthday instanceof \DateTimeInterface) {
            return $birthday->format('Y-m-d');
        }
        $s = trim((string) $birthday);
        if ($s === '' || $s === '0000-00-00') {
            return '';
        }
        if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $s, $m)) {
            return $m[1];
        }
        return $s;
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
