<?php
// 数据库迁移脚本 - 添加图片字段

$host = '127.0.0.1';
$dbname = 'pet_life';
$username = 'root';
$password = '123456';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 为post表添加image字段
    $pdo->exec("ALTER TABLE post ADD COLUMN image VARCHAR(255) DEFAULT NULL AFTER content");
    echo "post表添加image字段成功！\n";
    
    // 为comment表添加image字段
    $pdo->exec("ALTER TABLE comment ADD COLUMN image VARCHAR(255) DEFAULT NULL AFTER content");
    echo "comment表添加image字段成功！\n";
    
    echo "\n数据库迁移完成！\n";
} catch (PDOException $e) {
    echo "数据库迁移失败: " . $e->getMessage() . "\n";
}
?>