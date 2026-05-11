<?php
namespace app\index\model;

use think\Model;

class DailyCheckin extends Model
{
    protected $table = 'daily_checkins';

    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = false;

    protected $field = [
        'user_id',
        'checkin_date',
        'exp_awarded',
    ];
}