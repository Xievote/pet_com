<?php
namespace app\index\model;
use think\Model;

class User extends Model
{
    // 开启自动写入时间戳
    protected $autoWriteTimestamp = false;
    
    // 定义字段类型
    protected $type = [
        'birthday' => 'date',
    ];
    
    // 允许批量赋值的字段
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
        'create_time'
    ];
}
