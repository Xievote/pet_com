<?php
// 使用项目数据库配置创建宠物档案表
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
    
    // 创建宠物档案表
    $sql = "CREATE TABLE IF NOT EXISTS `pet_profiles` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `name` varchar(50) NOT NULL,
      `breed` varchar(50) DEFAULT '',
      `birthday` date DEFAULT NULL,
      `gender` enum('male','female','unknown') DEFAULT 'unknown',
      `avatar` varchar(255) DEFAULT '',
      `cover` varchar(255) DEFAULT '',
      `hobbies` varchar(500) DEFAULT '',
      `charm_score` decimal(3,1) DEFAULT '0.0',
      `created_at` datetime DEFAULT NULL,
      `updated_at` datetime DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `user_id` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $pdo->exec($sql);
    echo "宠物档案表创建成功！\n";
    
    // 创建宠物照片表
    $sql2 = "CREATE TABLE IF NOT EXISTS `pet_photos` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `pet_profile_id` int(11) NOT NULL,
      `image_path` varchar(255) NOT NULL,
      `description` varchar(255) DEFAULT '',
      `created_at` datetime DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `pet_profile_id` (`pet_profile_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $pdo->exec($sql2);
    echo "宠物照片表创建成功！\n";
    
    // 创建宠物记录关联表（用于时间线）
    $sql3 = "ALTER TABLE `pet_logs` ADD COLUMN IF NOT EXISTS `pet_profile_id` int(11) DEFAULT NULL;";
    $pdo->exec($sql3);
    echo "宠物记录表已添加pet_profile_id字段！\n";
    
} catch(PDOException $e) {
    echo "错误: " . $e->getMessage() . "\n";
}
?>