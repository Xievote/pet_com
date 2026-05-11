<?php
// 创建宠物档案表的脚本
$db_config = [
    'host' => 'localhost',
    'dbname' => 'pet_community',
    'username' => 'root',
    'password' => ''
];

try {
    $pdo = new PDO("mysql:host={$db_config['host']};charset=utf8", $db_config['username'], $db_config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 选择数据库
    $pdo->exec("USE {$db_config['dbname']}");
    
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
    
    // 创建宠物照片表（用于照片墙）
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
    
} catch(PDOException $e) {
    echo "错误: " . $e->getMessage() . "\n";
}
?>