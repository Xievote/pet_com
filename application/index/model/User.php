<?php
namespace app\index\model;
use think\Model;

class User extends Model
{
    // 开启自动写入时间戳（如果数据库有update_time字段）
    // 这里我们只用create_time
    protected $autoWriteTimestamp = false; 
}