<?php
namespace app\index\service;

use app\index\model\User;
use app\index\model\DailyCheckin;
use app\index\model\Achievement;
use app\index\model\UserAchievement;
use app\index\model\PetLog;
use app\index\model\Post;
use app\index\model\Like as LikeModel;
use app\index\model\Bookmark as BookmarkModel;
use think\Db;

class UserService
{
    const EXP_CHECKIN       = 10;
    const EXP_POST          = 30;
    const EXP_PET_LOG       = 20;
    const EXP_COMMENT       = 5;
    const EXP_RECEIVE_LIKE  = 2;

    const DAILY_LIMIT_POST     = 3;
    const DAILY_LIMIT_PET_LOG  = 3;
    const DAILY_LIMIT_COMMENT  = 10;
    const DAILY_LIMIT_LIKE     = 20;

    /**
     * 计算等级：level = floor(sqrt(exp / 100))
     */
    public static function calculateLevel($exp)
    {
        if ($exp < 100) {
            return 0;
        }
        return (int)floor(sqrt($exp / 100));
    }

    /**
     * 计算下一级所需经验
     */
    public static function expForNextLevel($level)
    {
        return pow($level + 1, 2) * 100;
    }

    /**
     * 每日签到
     */
    public static function dailyCheckin($userId)
    {
        $today = date('Y-m-d');

        $checkin = DailyCheckin::where('user_id', $userId)
            ->where('checkin_date', $today)
            ->find();

        if ($checkin) {
            return ['code' => 200, 'msg' => '今日已签到，请勿重复签到', 'duplicate' => true];
        }

        $user = User::find($userId);
        if (!$user) {
            return ['code' => 404, 'msg' => '用户不存在'];
        }

        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $lastDate = $user->last_checkin_date;
        $consecutiveDays = $user->checkin_days;

        if ($lastDate === $yesterday) {
            $consecutiveDays++;
        } else {
            $consecutiveDays = 1;
        }

        $bonusExp = 5 * $consecutiveDays;
        $totalExp = self::EXP_CHECKIN + $bonusExp;

        Db::startTrans();
        try {
            $dc = new DailyCheckin();
            $dc->user_id = $userId;
            $dc->checkin_date = $today;
            $dc->exp_awarded = $totalExp;
            $dc->created_at = date('Y-m-d H:i:s'); // 手动设置正确的时间格式
            $dc->save();

            $user->last_checkin_date = $today;
            $user->checkin_days = $consecutiveDays;
            $user->exp += $totalExp;
            $user->level = self::calculateLevel($user->exp);
            $user->save();

            Db::commit();

            self::checkAchievements($userId);

            return [
                'code' => 200,
                'msg' => '签到成功！获得 ' . $totalExp . ' 经验值',
                'data' => [
                    'exp_awarded' => $totalExp,
                    'base_exp' => self::EXP_CHECKIN,
                    'bonus_exp' => $bonusExp,
                    'consecutive_days' => $consecutiveDays,
                    'total_exp' => $user->exp,
                    'level' => $user->level,
                ]
            ];
        } catch (\Exception $e) {
            Db::rollback();
            return ['code' => 500, 'msg' => '签到失败：' . $e->getMessage()];
        }
    }

    /**
     * 增加经验值
     */
    public static function awardExp($userId, $type)
    {
        $expMap = [
            'post'      => self::EXP_POST,
            'pet_log'   => self::EXP_PET_LOG,
            'comment'   => self::EXP_COMMENT,
            'receive_like' => self::EXP_RECEIVE_LIKE,
        ];

        if (!isset($expMap[$type])) {
            return;
        }

        $limitMap = [
            'post'      => self::DAILY_LIMIT_POST,
            'pet_log'   => self::DAILY_LIMIT_PET_LOG,
            'comment'   => self::DAILY_LIMIT_COMMENT,
            'receive_like' => self::DAILY_LIMIT_LIKE,
        ];

        $limit = $limitMap[$type] ?? 0;
        $expAward = $expMap[$type];

        if ($limit > 0) {
            $todayCount = self::todayActionCount($userId, $type);
            if ($todayCount >= $limit) {
                return;
            }
        }

        $user = User::find($userId);
        if (!$user) {
            return;
        }

        Db::startTrans();
        try {
            $user->exp += $expAward;
            $user->level = self::calculateLevel($user->exp);
            $user->save();

            Db::commit();

            self::checkAchievements($userId);
        } catch (\Exception $e) {
            Db::rollback();
        }
    }

    /**
     * 统计今日某类操作次数
     */
    private static function todayActionCount($userId, $actionType)
    {
        $today = date('Y-m-d');
        $todayStart = $today . ' 00:00:00';
        $todayEnd = $today . ' 23:59:59';

        switch ($actionType) {
            case 'post':
                return Post::where('user_id', $userId)
                    ->where('created_at', '>=', $todayStart)
                    ->where('created_at', '<=', $todayEnd)
                    ->count();
            case 'pet_log':
                return PetLog::where('user_id', $userId)
                    ->where('create_time', '>=', $todayStart)
                    ->where('create_time', '<=', $todayEnd)
                    ->count();
            case 'comment':
                return \app\index\model\Comment::where('user_id', $userId)
                    ->where('created_at', '>=', $todayStart)
                    ->where('created_at', '<=', $todayEnd)
                    ->count();
            case 'receive_like':
                return LikeModel::alias('l')
                    ->join('pet_logs pl', 'l.target_type = \'pet_log\' AND l.target_id = pl.id')
                    ->where('pl.user_id', $userId)
                    ->where('l.created_at', '>=', $todayStart)
                    ->where('l.created_at', '<=', $todayEnd)
                    ->count()
                    +
                    LikeModel::alias('l')
                    ->join('post p', 'l.target_type = \'post\' AND l.target_id = p.id')
                    ->where('p.user_id', $userId)
                    ->where('l.created_at', '>=', $todayStart)
                    ->where('l.created_at', '<=', $todayEnd)
                    ->count();
            default:
                return 0;
        }
    }

    /**
     * 获取用户等级信息
     */
    public static function getUserLevelInfo($userId)
    {
        $user = User::field('exp,level,checkin_days,last_checkin_date')->find($userId);
        if (!$user) {
            return null;
        }

        $exp = (int)$user->exp;
        $level = (int)$user->level;
        $nextLevelExp = self::expForNextLevel($level);

        return [
            'exp' => $exp,
            'level' => $level,
            'next_level_exp' => $nextLevelExp,
            'progress' => $nextLevelExp > 0 ? round($exp / $nextLevelExp * 100, 1) : 100,
            'checkin_days' => (int)$user->checkin_days,
            'last_checkin_date' => $user->last_checkin_date,
            'checked_in_today' => $user->last_checkin_date === date('Y-m-d'),
        ];
    }

    /**
     * 检查并解锁成就
     */
    public static function checkAchievements($userId)
    {
        $allAchievements = Achievement::order('sort_order', 'asc')->select();
        if (empty($allAchievements)) {
            return [];
        }

        $unlockedIds = UserAchievement::where('user_id', $userId)->column('achievement_id');
        $unlocked = [];

        foreach ($allAchievements as $ach) {
            if (in_array($ach->id, $unlockedIds)) {
                continue;
            }

            $met = self::checkAchievementCondition($userId, $ach);
            if ($met) {
                $ua = new UserAchievement();
                $ua->user_id = $userId;
                $ua->achievement_id = $ach->id;
                $ua->save();

                $unlocked[] = [
                    'id' => $ach->id,
                    'code' => $ach->code,
                    'name' => $ach->name,
                    'description' => $ach->description,
                    'icon' => $ach->icon,
                ];
            }
        }

        return $unlocked;
    }

    /**
     * 检查单个成就条件
     */
    private static function checkAchievementCondition($userId, $achievement)
    {
        $value = (int)$achievement->condition_value;

        switch ($achievement->condition_type) {
            case 'post_count':
                return Post::where('user_id', $userId)->count() >= $value;
            case 'pet_log_count':
                return PetLog::where('user_id', $userId)->count() >= $value;
            case 'like_received':
                $petLogLikes = LikeModel::alias('l')
                    ->join('pet_logs pl', 'l.target_type = \'pet_log\' AND l.target_id = pl.id')
                    ->where('pl.user_id', $userId)
                    ->count();
                $postLikes = LikeModel::alias('l')
                    ->join('post p', 'l.target_type = \'post\' AND l.target_id = p.id')
                    ->where('p.user_id', $userId)
                    ->count();
                return ($petLogLikes + $postLikes) >= $value;
            case 'checkin_days':
                $user = User::field('checkin_days')->find($userId);
                return $user && (int)$user->checkin_days >= $value;
            case 'bookmark_received':
                return BookmarkModel::alias('b')
                    ->join('post p', 'b.target_type = \'post\' AND b.target_id = p.id')
                    ->where('p.user_id', $userId)
                    ->count() >= $value;
            default:
                return false;
        }
    }

    /**
     * 获取所有成就及用户解锁状态
     */
    public static function getAchievementsWithStatus($userId)
    {
        $allAchievements = Achievement::order('sort_order', 'asc')->select();
        $unlockedIds = UserAchievement::where('user_id', $userId)->column('achievement_id');

        $result = [];
        foreach ($allAchievements as $ach) {
            $result[] = [
                'id' => $ach->id,
                'code' => $ach->code,
                'name' => $ach->name,
                'description' => $ach->description,
                'icon' => $ach->icon,
                'condition_type' => $ach->condition_type,
                'condition_value' => (int)$ach->condition_value,
                'unlocked' => in_array($ach->id, $unlockedIds),
                'progress' => self::getAchievementProgress($userId, $ach),
            ];
        }

        return $result;
    }

    /**
     * 获取用户某成就的完成进度
     */
    private static function getAchievementProgress($userId, $achievement)
    {
        $value = (int)$achievement->condition_value;

        switch ($achievement->condition_type) {
            case 'post_count':
                $current = Post::where('user_id', $userId)->count();
                return ['current' => $current, 'target' => $value, 'percent' => min(100, round($current / $value * 100))];
            case 'pet_log_count':
                $current = PetLog::where('user_id', $userId)->count();
                return ['current' => $current, 'target' => $value, 'percent' => min(100, round($current / $value * 100))];
            case 'like_received':
                $petLogLikes = LikeModel::alias('l')
                    ->join('pet_logs pl', 'l.target_type = \'pet_log\' AND l.target_id = pl.id')
                    ->where('pl.user_id', $userId)
                    ->count();
                $postLikes = LikeModel::alias('l')
                    ->join('post p', 'l.target_type = \'post\' AND l.target_id = p.id')
                    ->where('p.user_id', $userId)
                    ->count();
                $current = $petLogLikes + $postLikes;
                return ['current' => $current, 'target' => $value, 'percent' => min(100, round($current / $value * 100))];
            case 'checkin_days':
                $user = User::field('checkin_days')->find($userId);
                $current = $user ? (int)$user->checkin_days : 0;
                return ['current' => $current, 'target' => $value, 'percent' => min(100, round($current / $value * 100))];
            case 'bookmark_received':
                $current = BookmarkModel::alias('b')
                    ->join('post p', 'b.target_type = \'post\' AND b.target_id = p.id')
                    ->where('p.user_id', $userId)
                    ->count();
                return ['current' => $current, 'target' => $value, 'percent' => min(100, round($current / $value * 100))];
            default:
                return ['current' => 0, 'target' => $value, 'percent' => 0];
        }
    }
}