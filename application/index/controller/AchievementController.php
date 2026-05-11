<?php
namespace app\index\controller;

use think\Controller;
use app\index\service\UserService;

class AchievementController extends Controller
{
    /**
     * 等级成就页面
     */
    public function index()
    {
        $userId = session('user_id');
        if (!$userId) {
            return $this->error('请先登录', '/login');
        }

        $levelInfo = UserService::getUserLevelInfo($userId);
        $achievements = UserService::getAchievementsWithStatus($userId);

        $this->assign('level_info', $levelInfo);
        $this->assign('achievements', $achievements);

        return $this->fetch('pet/achievement');
    }

    /**
     * 每日签到
     */
    public function checkin()
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $result = UserService::dailyCheckin($userId);
        return json($result);
    }

    /**
     * 获取等级信息
     */
    public function getLevelInfo()
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $levelInfo = UserService::getUserLevelInfo($userId);
        return json(['code' => 200, 'data' => $levelInfo]);
    }

    /**
     * 获取所有成就及解锁状态
     */
    public function getAchievements()
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $achievements = UserService::getAchievementsWithStatus($userId);
        return json(['code' => 200, 'data' => $achievements]);
    }

    /**
     * 检查成就（触发成就解锁检查）
     */
    public function checkAchievements()
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $newUnlocked = UserService::checkAchievements($userId);
        return json(['code' => 200, 'data' => $newUnlocked]);
    }
}