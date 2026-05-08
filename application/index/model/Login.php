<?php
namespace app\index\model;

use think\Model;

class User extends Model
{
    // 开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 密码修改器
     * 作用：在数据保存前，自动将密码进行哈希加密
     */
    public function setPasswordAttr($value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    /**
     * 状态获取器
     * 作用：在获取用户状态时，自动将数字转换为文字
     */
    public function getStatusAttr($value)
    {
        $status = [0 => '禁用', 1 => '正常'];
        return $status[$value];
    }
}