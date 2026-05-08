<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:64:"C:\Users\EDY\tp5\public/../application/index\view\pet\login.html";i:1778206827;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <title>登录</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; max-width: 400px; margin: 50px auto; padding: 20px; }
        h1 { text-align: center; margin-bottom: 30px; }
        .form-box { background: #f0f0f0; padding: 20px; border-radius: 8px; }
        input { width: 100%; padding: 10px; margin: 5px 0; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #ff9900; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; width: 100%; margin-top: 10px; }
        button:hover { background: #e68a00; }
        .register-link { text-align: center; margin-top: 20px; }
        .register-link a { color: #ff9900; text-decoration: none; }
        .register-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>登录</h1>
    
    <div class="form-box">
        <form action="/login" method="post">
            <input type="text" name="username" placeholder="用户名" required>
            <input type="password" name="password" placeholder="密码" required>
            <button type="submit">登录</button>
        </form>
    </div>
    
    <div class="register-link">
        还没有账号？<a href="/register">立即注册</a>
    </div>
</body>
</html>
