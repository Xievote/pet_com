<?php
namespace app\index\model;
use think\Model;

class User extends Model
{
    protected $autoWriteTimestamp = false;

    protected $type = [
        'birthday' => 'date',
    ];

    protected $field = [
        'username',
        'password',
        'avatar',
        'bio',
        'gender',
        'birthday',
        'hometown',
        'zodiac',
        'mbti',
        'create_time',
        'exp',
        'level',
        'last_checkin_date',
        'checkin_days',
    ];
}
