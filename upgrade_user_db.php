<?php
/**
 * 为已有 user 表补充 User 模型所需字段（与 create_user_table 全量结构对齐）。
 * 重复执行会跳过已存在的列。
 */
$dsn = 'mysql:host=127.0.0.1;dbname=pet_life;port=3306;charset=utf8mb4';
$username = 'root';
$password = '123456';

$columns = [
    'avatar' => 'VARCHAR(255) DEFAULT NULL COMMENT \'头像URL\'',
    'bio' => 'TEXT NULL COMMENT \'个人简介\'',
    'gender' => 'VARCHAR(20) DEFAULT \'secret\' COMMENT \'性别\'',
    'birthday' => 'DATE DEFAULT NULL COMMENT \'生日\'',
    'hometown' => 'VARCHAR(100) DEFAULT NULL COMMENT \'故乡\'',
    'zodiac' => 'VARCHAR(32) DEFAULT NULL COMMENT \'星座\'',
    'mbti' => 'VARCHAR(16) DEFAULT NULL COMMENT \'MBTI\'',
];

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    foreach ($columns as $name => $definition) {
        try {
            $pdo->exec("ALTER TABLE `user` ADD COLUMN `{$name}` {$definition}");
            echo "Added column: {$name}\n";
        } catch (PDOException $e) {
            if ($e->getCode() == '42S21' || strpos($e->getMessage(), 'Duplicate column') !== false) {
                echo "Skip (exists): {$name}\n";
            } else {
                throw $e;
            }
        }
    }
    echo "Done.\n";
} catch (PDOException $e) {
    fwrite(STDERR, 'Error: ' . $e->getMessage() . "\n");
    exit(1);
}
