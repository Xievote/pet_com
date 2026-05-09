<?php
/**
 * 创建或提升超级管理员账号（仅命令行执行）。
 * 用法：php ensure_super_admin.php <用户名> <密码>
 * 会将该用户 is_super_admin 设为 1 并更新密码哈希。
 */
if (php_sapi_name() !== 'cli') {
    exit('CLI only');
}

if ($argc < 3) {
    fwrite(STDERR, "用法: php ensure_super_admin.php <用户名> <密码>\n");
    exit(1);
}

$username = $argv[1];
$plain = $argv[2];
if (strlen($plain) < 8) {
    fwrite(STDERR, "密码至少 8 位。\n");
    exit(1);
}

$dsn = 'mysql:host=127.0.0.1;dbname=pet_life;port=3306;charset=utf8mb4';
$dbUser = 'root';
$dbPass = '123456';

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 确保列存在（与 upgrade_user_db 一致）
    try {
        $pdo->exec("ALTER TABLE `user` ADD COLUMN `is_super_admin` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1=超级管理员'");
        echo "Added column is_super_admin\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') === false) {
            throw $e;
        }
    }

    $hash = password_hash($plain, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('SELECT id FROM `user` WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $u = $pdo->prepare('UPDATE `user` SET password = ?, is_super_admin = 1 WHERE id = ?');
        $u->execute([$hash, $row['id']]);
        echo "已将该用户设为超级管理员并更新密码: {$username} (id={$row['id']})\n";
    } else {
        $t = time();
        $ins = $pdo->prepare('INSERT INTO `user` (username, password, is_super_admin, create_time) VALUES (?, ?, 1, ?)');
        $ins->execute([$username, $hash, $t]);
        echo "已创建超级管理员: {$username}\n";
    }
} catch (PDOException $e) {
    fwrite(STDERR, '数据库错误: ' . $e->getMessage() . "\n");
    exit(1);
}
