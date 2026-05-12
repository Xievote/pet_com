<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"C:\Users\EDY\tp5\public/../application/index\view\pet\create_log.html";i:1778555811;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <title>记录宠物生活</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #fef9f3 0%, #fff8f0 100%); min-height: 100vh; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 24px; color: #333; }
        .back-link { color: #ff9900; text-decoration: none; font-size: 14px; }
        .back-link:hover { text-decoration: underline; }
        
        .form-card { background: white; border-radius: 16px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin-bottom: 20px; }
        .form-card h2 { margin: 0 0 20px; font-size: 20px; color: #333; display: flex; align-items: center; gap: 8px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 15px; }
        .form-group input, .form-group textarea { width: 100%; padding: 14px; border: 2px solid #eee; border-radius: 10px; font-size: 15px; transition: border-color 0.3s; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #ff9900; }
        .form-group textarea { resize: vertical; min-height: 150px; }
        
        .upload-section { display: flex; gap: 15px; align-items: center; flex-wrap: wrap; }
        .upload-label { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: linear-gradient(135deg, #3498db, #2980b9); color: white; border-radius: 10px; cursor: pointer; font-size: 14px; transition: all 0.3s; box-shadow: 0 4px 12px rgba(52,152,219,0.3); }
        .upload-label:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(52,152,219,0.4); }
        .image-preview { max-width: 200px; max-height: 200px; border-radius: 10px; margin-top: 15px; }
        
        .form-actions { display: flex; gap: 15px; margin-top: 25px; }
        .btn { padding: 14px 32px; border: none; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn-primary { background: linear-gradient(135deg, #ff9900, #ff6600); color: white; box-shadow: 0 4px 15px rgba(255,153,0,0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,153,0,0.4); }
        .btn-secondary { background: #f5f5f5; color: #666; }
        .btn-secondary:hover { background: #eee; }
        
        .toast { position: fixed; top: 20px; left: 50%; transform: translateX(-50%) translateY(-100px); background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; padding: 14px 28px; border-radius: 10px; box-shadow: 0 8px 25px rgba(231,76,60,0.4); z-index: 9999; opacity: 0; transition: all 0.4s ease; font-size: 15px; }
        .toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }
        
        @media (max-width: 600px) { body { padding: 15px; } .form-card { padding: 20px; } }
    </style>
</head>
<body>
    <div class="toast" id="toast"></div>

    <div class="header">
        <h1>📝 记录宠物生活</h1>
        <a href="/index" class="back-link">← 返回首页</a>
    </div>

    <div class="form-card">
        <h2>🐾 记录宠物生活</h2>
        <form action="/pet/save" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <input type="hidden" name="csrf_token" value="<?php echo (isset($csrf_token) && ($csrf_token !== '')?$csrf_token:''); ?>">
            
            <div class="form-group">
                <label for="petName">宠物名字 *</label>
                <input type="text" id="petName" name="pet_name" placeholder="例如：旺财" required maxlength="50">
            </div>
            
            <div class="form-group">
                <label for="petContent">记录内容 *</label>
                <textarea id="petContent" name="content" placeholder="今天发生了什么？（例如：今天带宠物去公园散步，宠物非常开心）" required></textarea>
            </div>
            
            <div class="form-group">
                <label>上传图片（可选）</label>
                <div class="upload-section">
                    <label class="upload-label">
                        📷 添加图片
                        <input type="file" name="image" accept="image/*" onchange="previewImage(this, 'imagePreview')">
                    </label>
                </div>
                <img id="imagePreview" class="image-preview" alt="图片预览">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">✨ 发布记录</button>
                <button type="button" class="btn btn-secondary" onclick="location.href='/index'">取消</button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(input, previewId) {
            var preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
            }
        }
        
        function validateForm() {
            var petName = document.getElementById('petName').value.trim();
            var content = document.getElementById('petContent').value.trim();
            
            if (!petName) { showToast('请输入宠物名字'); return false; }
            if (!content) { showToast('请输入记录内容'); return false; }
            
            return true;
        }
        
        function showToast(message) {
            var toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.add('show');
            setTimeout(function() { toast.classList.remove('show'); }, 2500);
        }
    </script>
</body>
</html>