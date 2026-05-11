<?php
namespace app\index\model;

use think\Model;

class DailyCheckin extends Model
{
    protected $table = 'daily_checkins';

    // 关闭自动写入时间戳，手动处理
    protected $autoWriteTimestamp = false;

    protected $field = [
        'user_id',
        'checkin_date',
        'exp_awarded',
        'created_at',
    ];
}