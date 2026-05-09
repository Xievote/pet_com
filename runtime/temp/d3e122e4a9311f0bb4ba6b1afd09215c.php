<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"C:\Users\EDY\tp5\public/../application/index\view\pet\profile.html";i:1778319221;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <title>个人信息</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; background: #f5f5f5; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { margin: 0; }
        .back-link { color: #ff9900; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        
        .profile-container { display: grid; grid-template-columns: 300px 1fr; gap: 30px; }
        @media (max-width: 768px) {
            .profile-container { grid-template-columns: 1fr; }
        }
        
        /* 左侧头像卡片 */
        .avatar-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
        .avatar-wrapper { position: relative; width: 150px; height: 150px; margin: 0 auto 20px; }
        .avatar-preview { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 3px solid #ff9900; }
        .avatar-placeholder { width: 150px; height: 150px; border-radius: 50%; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 60px; color: #999; border: 3px dashed #ddd; }
        .avatar-upload-btn { position: absolute; bottom: 5px; right: 5px; width: 40px; height: 40px; background: #ff9900; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: white; font-size: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        .avatar-upload-btn:hover { background: #e68a00; }
        #avatarInput { display: none; }
        .avatar-hint { color: #999; font-size: 12px; margin-top: 10px; }
        
        /* 拖拽上传区域 */
        .avatar-drop-zone { border: 2px dashed #ddd; border-radius: 12px; padding: 20px; margin-top: 15px; transition: all 0.3s; }
        .avatar-drop-zone.drag-over { border-color: #ff9900; background: #fff8f0; }
        .avatar-drop-zone p { margin: 0; color: #999; font-size: 14px; }
        
        /* 右侧表单 */
        .form-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-section { margin-bottom: 25px; }
        .form-section h3 { margin-bottom: 15px; color: #333; border-left: 4px solid #ff9900; padding-left: 10px; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group select,
        .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: border-color 0.3s; }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus { outline: none; border-color: #ff9900; }
        .form-group textarea { resize: vertical; min-height: 100px; }
        .char-count { text-align: right; color: #999; font-size: 12px; margin-top: 5px; }
        .char-count.warning { color: #e74c3c; }
        
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 600px) {
            .form-row { grid-template-columns: 1fr; }
        }
        
        .radio-group { display: flex; gap: 20px; flex-wrap: wrap; }
        .radio-group label { display: flex; align-items: center; gap: 5px; font-weight: normal; cursor: pointer; }
        .radio-group input[type="radio"] { width: auto; }
        
        .form-actions { display: flex; gap: 15px; justify-content: flex-end; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
        .btn { padding: 12px 30px; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; transition: all 0.3s; }
        .btn-primary { background: #ff9900; color: white; }
        .btn-primary:hover { background: #e68a00; }
        .btn-secondary { background: #f0f0f0; color: #333; }
        .btn-secondary:hover { background: #e0e0e0; }
        .btn:disabled { opacity: 0.6; cursor: not-allowed; }
        
        /* 图片裁剪模态框 */
        .crop-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; justify-content: center; align-items: center; }
        .crop-modal.show { display: flex; }
        .crop-container { background: white; padding: 20px; border-radius: 12px; max-width: 500px; width: 90%; }
        .crop-container h3 { margin-bottom: 20px; text-align: center; }
        .crop-preview { width: 100%; max-height: 400px; overflow: hidden; border-radius: 8px; margin-bottom: 20px; }
        .crop-preview img { max-width: 100%; display: block; }
        .crop-actions { display: flex; gap: 10px; justify-content: center; }
        
        /* Toast提示 */
        .toast { position: fixed; top: 20px; left: 50%; transform: translateX(-50%) translateY(-100px); background: #27ae60; color: white; padding: 15px 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 9999; opacity: 0; transition: all 0.3s ease; }
        .toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }
        .toast.error { background: #e74c3c; }
        
        /* 加载状态 */
        .loading { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9998; justify-content: center; align-items: center; }
        .loading.show { display: flex; }
        .spinner { width: 50px; height: 50px; border: 4px solid #f3f3f3; border-top: 4px solid #ff9900; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <!-- Toast提示 -->
    <div class="toast" id="toast"></div>
    
    <!-- 加载状态 -->
    <div class="loading" id="loading">
        <div class="spinner"></div>
    </div>
    
    <!-- 图片裁剪模态框 -->
    <div class="crop-modal" id="cropModal">
        <div class="crop-container">
            <h3>调整头像</h3>
            <div class="crop-preview">
                <img id="cropImage" src="" alt="裁剪预览">
            </div>
            <div class="crop-actions">
                <button class="btn btn-primary" onclick="confirmCrop()">确认使用</button>
                <button class="btn btn-secondary" onclick="cancelCrop()">重新选择</button>
            </div>
        </div>
    </div>

    <!-- 头部 -->
    <div class="header">
        <h1>👤 个人信息</h1>
        <a href="/index" class="back-link">← 返回首页</a>
    </div>
    
    <form action="/profile" method="post" enctype="multipart/form-data" id="profileForm">
        <div class="profile-container">
            <!-- 左侧头像卡片 -->
            <div class="avatar-card">
                <div class="avatar-wrapper">
                    <?php if($user['avatar']): ?>
                    <img src="<?php echo $user['avatar']; ?>" alt="头像" class="avatar-preview" id="avatarPreview">
                    <?php else: ?>
                    <div class="avatar-placeholder" id="avatarPlaceholder"><?php echo strtoupper(substr($user['username'],0,1)); ?></div>
                    <?php endif; ?>
                    <label class="avatar-upload-btn" title="更换头像">
                        📷
                        <input type="file" id="avatarInput" name="avatar" accept="image/jpeg,image/png,image/jpg" onchange="handleAvatarSelect(event)">
                    </label>
                </div>
                <input type="hidden" name="cropped_avatar" id="croppedAvatar">
                <p class="avatar-hint">点击相机图标或拖拽图片上传<br>支持 JPG、PNG 格式，最大 5MB</p>
                
                <div class="avatar-drop-zone" id="dropZone">
                    <p>📁 拖拽图片到此处上传</p>
                </div>
            </div>
            
            <!-- 右侧表单卡片 -->
            <div class="form-card">
                <!-- 基本信息 -->
                <div class="form-section">
                    <h3>基本信息</h3>
                    <div class="form-group">
                        <label for="username">用户名</label>
                        <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required maxlength="50">
                    </div>
                    
                    <div class="form-group">
                        <label>性别</label>
                        <div class="radio-group">
                            <label><input type="radio" name="gender" value="male" <?php if($user['gender']=='male'): ?>checked<?php endif; ?>> 男</label>
                            <label><input type="radio" name="gender" value="female" <?php if($user['gender']=='female'): ?>checked<?php endif; ?>> 女</label>
                            <label><input type="radio" name="gender" value="secret" <?php if($user['gender']=='secret' || !$user['gender']): ?>checked<?php endif; ?>> 保密</label>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="birthday">生日</label>
                            <input type="date" id="birthday" name="birthday" value="<?php echo $user['birthday']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="zodiac">星座</label>
                            <select id="zodiac" name="zodiac">
                                <option value="">请选择星座</option>
                                <option value="aries" <?php if($user['zodiac']=='aries'): ?>selected<?php endif; ?>>♈ 白羊座 (3.21-4.19)</option>
                                <option value="taurus" <?php if($user['zodiac']=='taurus'): ?>selected<?php endif; ?>>♉ 金牛座 (4.20-5.20)</option>
                                <option value="gemini" <?php if($user['zodiac']=='gemini'): ?>selected<?php endif; ?>>♊ 双子座 (5.21-6.21)</option>
                                <option value="cancer" <?php if($user['zodiac']=='cancer'): ?>selected<?php endif; ?>>♋ 巨蟹座 (6.22-7.22)</option>
                                <option value="leo" <?php if($user['zodiac']=='leo'): ?>selected<?php endif; ?>>♌ 狮子座 (7.23-8.22)</option>
                                <option value="virgo" <?php if($user['zodiac']=='virgo'): ?>selected<?php endif; ?>>♍ 处女座 (8.23-9.22)</option>
                                <option value="libra" <?php if($user['zodiac']=='libra'): ?>selected<?php endif; ?>>♎ 天秤座 (9.23-10.23)</option>
                                <option value="scorpio" <?php if($user['zodiac']=='scorpio'): ?>selected<?php endif; ?>>♏ 天蝎座 (10.24-11.22)</option>
                                <option value="sagittarius" <?php if($user['zodiac']=='sagittarius'): ?>selected<?php endif; ?>>♐ 射手座 (11.23-12.21)</option>
                                <option value="capricorn" <?php if($user['zodiac']=='capricorn'): ?>selected<?php endif; ?>>♑ 摩羯座 (12.22-1.19)</option>
                                <option value="aquarius" <?php if($user['zodiac']=='aquarius'): ?>selected<?php endif; ?>>♒ 水瓶座 (1.20-2.18)</option>
                                <option value="pisces" <?php if($user['zodiac']=='pisces'): ?>selected<?php endif; ?>>♓ 双鱼座 (2.19-3.20)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="hometown">故乡</label>
                            <input type="text" id="hometown" name="hometown" value="<?php echo $user['hometown']; ?>" placeholder="请输入您的故乡" maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="mbti">MBTI性格类型</label>
                            <select id="mbti" name="mbti">
                                <option value="">请选择MBTI类型</option>
                                <option value="INTJ" <?php if($user['mbti']=='INTJ'): ?>selected<?php endif; ?>>INTJ - 建筑师</option>
                                <option value="INTP" <?php if($user['mbti']=='INTP'): ?>selected<?php endif; ?>>INTP - 逻辑学家</option>
                                <option value="ENTJ" <?php if($user['mbti']=='ENTJ'): ?>selected<?php endif; ?>>ENTJ - 指挥官</option>
                                <option value="ENTP" <?php if($user['mbti']=='ENTP'): ?>selected<?php endif; ?>>ENTP - 辩论家</option>
                                <option value="INFJ" <?php if($user['mbti']=='INFJ'): ?>selected<?php endif; ?>>INFJ - 提倡者</option>
                                <option value="INFP" <?php if($user['mbti']=='INFP'): ?>selected<?php endif; ?>>INFP - 调停者</option>
                                <option value="ENFJ" <?php if($user['mbti']=='ENFJ'): ?>selected<?php endif; ?>>ENFJ - 主人公</option>
                                <option value="ENFP" <?php if($user['mbti']=='ENFP'): ?>selected<?php endif; ?>>ENFP - 竞选者</option>
                                <option value="ISTJ" <?php if($user['mbti']=='ISTJ'): ?>selected<?php endif; ?>>ISTJ - 检查员</option>
                                <option value="ISFJ" <?php if($user['mbti']=='ISFJ'): ?>selected<?php endif; ?>>ISFJ - 守卫者</option>
                                <option value="ESTJ" <?php if($user['mbti']=='ESTJ'): ?>selected<?php endif; ?>>ESTJ - 总经理</option>
                                <option value="ESFJ" <?php if($user['mbti']=='ESFJ'): ?>selected<?php endif; ?>>ESFJ - 执政官</option>
                                <option value="ISTP" <?php if($user['mbti']=='ISTP'): ?>selected<?php endif; ?>>ISTP - 鉴赏家</option>
                                <option value="ISFP" <?php if($user['mbti']=='ISFP'): ?>selected<?php endif; ?>>ISFP - 探险家</option>
                                <option value="ESTP" <?php if($user['mbti']=='ESTP'): ?>selected<?php endif; ?>>ESTP - 企业家</option>
                                <option value="ESFP" <?php if($user['mbti']=='ESFP'): ?>selected<?php endif; ?>>ESFP - 表演者</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- 个性描述 -->
                <div class="form-section">
                    <h3>个性描述</h3>
                    <div class="form-group">
                        <label for="bio">个人简介（200-500字）</label>
                        <textarea id="bio" name="bio" maxlength="500" placeholder="介绍一下自己吧..."><?php echo $user['bio']; ?></textarea>
                        <div class="char-count" id="charCount">0 / 500</div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">重置</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">保存修改</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        // 字符计数
        const bioTextarea = document.getElementById('bio');
        const charCount = document.getElementById('charCount');
        
        function updateCharCount() {
            const length = bioTextarea.value.length;
            charCount.textContent = length + ' / 500';
            if (length > 450) {
                charCount.classList.add('warning');
            } else {
                charCount.classList.remove('warning');
            }
        }
        
        bioTextarea.addEventListener('input', updateCharCount);
        updateCharCount(); // 初始化
        
        // 头像处理
        let selectedFile = null;
        const avatarInput = document.getElementById('avatarInput');
        const dropZone = document.getElementById('dropZone');
        const cropModal = document.getElementById('cropModal');
        const cropImage = document.getElementById('cropImage');
        
        // 处理头像选择
        function handleAvatarSelect(event) {
            const file = event.target.files[0];
            if (file) {
                processAvatarFile(file);
            }
        }
        
        // 处理头像文件
        function processAvatarFile(file) {
            // 验证文件类型
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                showToast('请选择 JPG 或 PNG 格式的图片', 'error');
                return;
            }
            
            // 验证文件大小（5MB）
            if (file.size > 5 * 1024 * 1024) {
                showToast('图片大小不能超过 5MB', 'error');
                return;
            }
            
            selectedFile = file;
            
            // 读取并显示图片
            const reader = new FileReader();
            reader.onload = function(e) {
                cropImage.src = e.target.result;
                cropModal.classList.add('show');
            };
            reader.readAsDataURL(file);
        }
        
        // 拖拽上传
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });
        
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('drag-over');
        });
        
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                processAvatarFile(files[0]);
            }
        });
        
        // 确认裁剪（简化版，实际项目中可以使用cropper.js等库）
        function confirmCrop() {
            // 将裁剪后的图片数据保存到隐藏字段
            document.getElementById('croppedAvatar').value = cropImage.src;
            
            // 更新预览
            const preview = document.getElementById('avatarPreview');
            const placeholder = document.getElementById('avatarPlaceholder');
            if (preview) {
                preview.src = cropImage.src;
            } else if (placeholder) {
                // 创建新的img元素替换placeholder
                const newImg = document.createElement('img');
                newImg.src = cropImage.src;
                newImg.className = 'avatar-preview';
                newImg.id = 'avatarPreview';
                placeholder.parentNode.replaceChild(newImg, placeholder);
            }
            
            cropModal.classList.remove('show');
            showToast('头像已更新，请保存修改');
        }
        
        // 取消裁剪
        function cancelCrop() {
            cropModal.classList.remove('show');
            avatarInput.value = '';
            selectedFile = null;
        }
        
        // 表单提交
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const saveBtn = document.getElementById('saveBtn');
            saveBtn.disabled = true;
            saveBtn.textContent = '保存中...';
            
            const formData = new FormData(this);
            
            // 如果有裁剪后的头像数据，转换为Blob
            const croppedData = document.getElementById('croppedAvatar').value;
            if (croppedData && croppedData.startsWith('data:image')) {
                // 将base64转换为文件
                fetch(croppedData)
                    .then(res => res.blob())
                    .then(blob => {
                        formData.append('avatar_file', blob, 'avatar.jpg');
                        submitForm(formData);
                    });
            } else {
                submitForm(formData);
            }
        });
        
        function submitForm(formData) {
            fetch('/profile', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.code === 200) {
                    showToast(data.msg || '保存成功');
                    window.location.reload();
                } else {
                    showToast(data.msg || '保存失败', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('保存失败，请稍后重试', 'error');
            })
            .finally(() => {
                const saveBtn = document.getElementById('saveBtn');
                saveBtn.disabled = false;
                saveBtn.textContent = '保存修改';
            });
        }
        
        // 重置表单
        function resetForm() {
            if (confirm('确定要重置所有修改吗？')) {
                document.getElementById('profileForm').reset();
                updateCharCount();
            }
        }
        
        // 显示Toast提示
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = 'toast ' + (type === 'error' ? 'error' : '');
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
        
        // 根据生日自动计算星座
        document.getElementById('birthday').addEventListener('change', function() {
            const birthday = this.value;
            if (birthday) {
                const date = new Date(birthday);
                const month = date.getMonth() + 1;
                const day = date.getDate();
                const zodiac = getZodiac(month, day);
                if (zodiac) {
                    document.getElementById('zodiac').value = zodiac;
                }
            }
        });
        
        function getZodiac(month, day) {
            const zodiacDates = [
                { name: 'capricorn', start: [1, 1], end: [1, 19] },
                { name: 'aquarius', start: [1, 20], end: [2, 18] },
                { name: 'pisces', start: [2, 19], end: [3, 20] },
                { name: 'aries', start: [3, 21], end: [4, 19] },
                { name: 'taurus', start: [4, 20], end: [5, 20] },
                { name: 'gemini', start: [5, 21], end: [6, 21] },
                { name: 'cancer', start: [6, 22], end: [7, 22] },
                { name: 'leo', start: [7, 23], end: [8, 22] },
                { name: 'virgo', start: [8, 23], end: [9, 22] },
                { name: 'libra', start: [9, 23], end: [10, 23] },
                { name: 'scorpio', start: [10, 24], end: [11, 22] },
                { name: 'sagittarius', start: [11, 23], end: [12, 21] },
                { name: 'capricorn2', start: [12, 22], end: [12, 31] }
            ];
            
            for (let z of zodiacDates) {
                const [startMonth, startDay] = z.start;
                const [endMonth, endDay] = z.end;
                
                if (month === startMonth && day >= startDay) {
                    return z.name === 'capricorn2' ? 'capricorn' : z.name;
                }
                if (month === endMonth && day <= endDay) {
                    return z.name === 'capricorn2' ? 'capricorn' : z.name;
                }
                if (startMonth < endMonth) {
                    if (month > startMonth && month < endMonth) {
                        return z.name === 'capricorn2' ? 'capricorn' : z.name;
                    }
                }
            }
            return '';
        }
    </script>
</body>
</html>