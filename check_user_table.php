<?php
// 直接使用PDO连接数据库
$dsn = 'mysql:host=127.0.0.1;dbname=pet_life;port=3306;charset=utf8';
$username = 'root';
$password = '123456';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 查看 user 表结构
    $stmt = $pdo->query('DESCRIBE user');
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($result);
    
    // 查看 user 表中的数据
    $stmt = $pdo->query('SELECT * FROM user');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "\nUser data:\n";
    print_r($users);
} catch (PDOException $e) {
    echo '数据库连接失败: ' . $e->getMessage();
}
