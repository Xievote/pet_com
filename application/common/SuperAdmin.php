<?php
namespace app\common;

use app\index\model\User;

/**
 * 超级管理员权限以数据库为准，删除等敏感操作须调用 verifyFromDb，不可仅信任 Session。
 */
class SuperAdmin
{
    /**
     * @param int|string|null $userId
     * @return bool
     */
    public static function verifyFromDb($userId)
    {
        if ($userId === null || $userId === '') {
            return false;
        }
        $user = User::field('id,is_super_admin')->find($userId);
        return $user && (int) $user->is_super_admin === 1;
    }
}
