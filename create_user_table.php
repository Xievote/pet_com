<?php
// 直接使用PDO连接数据库
$dsn = 'mysql:host=127.0.0.1;dbname=pet_life;port=3306;charset=utf8';
$username = 'root';
$password = '123456';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 创建 user 表
    $sql = 'CREATE TABLE IF NOT EXISTS user (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        create_time INT DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;';
    $pdo->exec($sql);
    
    echo 'user 表创建成功！';
} catch (PDOException $e) {
    echo '数据库连接失败: ' . $e->getMessage();
}
