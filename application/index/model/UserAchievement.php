<?php
namespace app\index\model;

use think\Model;

class UserAchievement extends Model
{
    protected $table = 'user_achievements';

    protected $autoWriteTimestamp = true;
    protected $createTime = 'unlocked_at';
    protected $updateTime = false;

    protected $field = [
        'user_id',
        'achievement_id',
    ];
}