<?php
// 简单的数据库连接测试脚本
require_once 'vendor/autoload.php';

use think\Db;

// 获取数据库配置
$config = [
    'type' => 'mysql',
    'hostname' => '127.0.0.1',
    'database' => 'pet_life',
    'username' => 'root',
    'password' => '123456',
    'hostport' => '3306',
    'charset' => 'utf8mb4'
];

try {
    // 直接使用PDO创建表
    $dsn = "mysql:host={$config['hostname']};dbname={$config['database']};port={$config['hostport']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "数据库连接成功！\n";
    
    // 测试查询
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "现有表: " . implode(', ', $tables) . "\n";
    
} catch(PDOException $e) {
    echo "数据库连接错误: " . $e->getMessage() . "\n";
}
?>