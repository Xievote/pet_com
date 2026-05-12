<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"C:\Users\EDY\tp5\public/../application/index\view\post\create.html";i:1778316905;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>创建帖子</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
            min-height: 200px;
            font-size: 16px;
        }
        .form-actions {
            text-align: right;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-secondary {
            background-color: #f44336;
        }
        .btn-secondary:hover {
            background-color: #da190b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>创建帖子</h1>
        <form action="/post/save" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo (isset($csrf_token) && ($csrf_token !== '')?$csrf_token:''); ?>">
            <div class="form-group">
                <label for="title">标题</label>
                <input type="text" id="title" name="title" placeholder="请输入帖子标题">
            </div>
            <div class="form-group">
                <label for="content">内容</label>
                <textarea id="content" name="content" placeholder="请输入帖子内容"></textarea>
            </div>
            <div class="form-actions">
                <a href="/post" class="btn btn-secondary">取消</a>
                <input type="submit" value="发布帖子" class="btn">
            </div>
        </form>
    </div>
</body>
</html>
