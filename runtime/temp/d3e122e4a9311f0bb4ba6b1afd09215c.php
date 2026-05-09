<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"C:\Users\EDY\tp5\public/../application/index\view\pet\profile.html";i:1778138754;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <title>个人信息</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        h1 { text-align: center; margin-bottom: 30px; }
        .form-box { background: #f0f0f0; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        input { width: 100%; padding: 10px; margin: 5px 0; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #ff9900; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; }
        button:hover { background: #e68a00; }
        .form-actions { text-align: right; margin-top: 20px; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #ff9900; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>个人信息</h1>
    
    <div class="form-box">
        <form action="<?php echo url('profile'); ?>" method="post">
            <div class="form-group">
                <label for="username">用户名</label>
                <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit">更新信息</button>
            </div>
        </form>
    </div>
    
    <a href="<?php echo url('index'); ?>" class="back-link">返回首页</a>
</body>
</html>
