<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:64:"C:\Users\EDY\tp5\public/../application/index\view\pet\index.html";i:1778232030;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <title>宠物生活记录</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { margin: 0; }
        .user-info { position: relative; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: #ff9900; color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; }
        .user-dropdown { position: absolute; top: 50px; right: 0; background: white; border: 1px solid #ddd; border-radius: 8px; padding: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 100; display: none; }
        .user-dropdown.show { display: block; }
        .user-dropdown a { display: block; padding: 8px 16px; text-decoration: none; color: #333; }
        .user-dropdown a:hover { background: #f5f5f5; }
        .form-box { background: #f0f0f0; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        input, textarea { width: 100%; padding: 10px; margin: 5px 0; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #ff9900; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; }
        button:hover { background: #e68a00; }
        button.delete-btn { background: #e74c3c; }
        button.delete-btn:hover { background: #c0392b; }
        button:disabled { background: #ccc; cursor: not-allowed; }
        .log-item { border-bottom: 1px solid #eee; padding: 15px 0; }
        .time { color: #999; font-size: 12px; }
        .section { margin-bottom: 40px; }
        .section h2 { margin-bottom: 20px; }
        .post-item { border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .post-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .post-author { font-weight: bold; }
        .post-title { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .post-content { margin-bottom: 15px; line-height: 1.5; }
        .post-image { max-width: 100%; height: auto; border-radius: 4px; margin-bottom: 10px; }
        .post-actions { display: flex; gap: 10px; }
        .comment-section { margin-top: 20px; }
        .comment-form { margin-bottom: 20px; }
        .comment-item { border-left: 3px solid #ff9900; padding-left: 15px; margin-bottom: 15px; }
        .comment-header { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 5px; }
        .comment-author { font-weight: bold; }
        .comment-time { color: #999; font-size: 12px; }
        .load-more { text-align: center; margin: 20px 0; }
        .load-more button { background: #f5f5f5; color: #333; }
        .load-more button:hover { background: #e0e0e0; }
        
        /* 分页样式 */
        .pagination { display: flex; justify-content: center; align-items: center; gap: 8px; margin: 20px 0; }
        .pagination a, .pagination span { padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #333; }
        .pagination a:hover { background: #f5f5f5; }
        .pagination .active { background: #ff9900; color: white; border-color: #ff9900; }
        .pagination .disabled { opacity: 0.5; cursor: not-allowed; }
        
        /* 图片预览样式 */
        .image-preview { margin: 10px 0; max-width: 200px; max-height: 200px; display: none; }
        .image-preview.show { display: block; }
        
        /* 上传进度 */
        .upload-progress { height: 10px; background: #eee; border-radius: 5px; margin: 10px 0; overflow: hidden; }
        .upload-progress-bar { height: 100%; background: #ff9900; width: 0%; transition: width 0.3s; }
        
        /* 弹窗样式 */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000; }
        .modal.show { display: flex; }
        .modal-content { background: white; padding: 20px; border-radius: 8px; text-align: center; min-width: 300px; }
        .modal-buttons { display: flex; gap: 10px; justify-content: center; margin-top: 20px; }
        .modal-buttons button { padding: 8px 20px; }
        
        @media (max-width: 600px) {
            body { padding: 10px; }
            .header { flex-direction: column; align-items: flex-start; gap: 10px; }
            .user-info { align-self: flex-end; }
            .pagination { flex-wrap: wrap; }
        }
    </style>
</head>
<body>
    <!-- 确认弹窗 -->
    <div class="modal" id="confirmModal">
        <div class="modal-content">
            <p id="modalMessage">确定要删除吗？</p>
            <div class="modal-buttons">
                <button onclick="confirmDelete()">确定</button>
                <button onclick="cancelDelete()">取消</button>
            </div>
        </div>
    </div>

    <!-- 头部区域 -->
    <div class="header">
        <h1>🐶 铲屎官记录本</h1>
        <div class="user-info">
            <div class="user-avatar" onclick="toggleUserDropdown()">
                <?php if(session('username')): ?><?php echo substr(\think\Session::get('username'),0,1); else: ?>登<?php endif; ?>
            </div>
            <div class="user-dropdown" id="userDropdown">
                <?php if(session('user_id')): ?>
                <a href="/profile">个人信息</a>
                <a href="/logout">退出登录</a>
                <?php else: ?>
                <a href="/login">登录</a>
                <a href="/register">注册</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- 宠物记录表单区域 -->
    <div class="section">
        <h2>📝 记录宠物生活</h2>
        <div class="form-box">
            <form action="/pet/save" method="post" enctype="multipart/form-data">
                <input type="text" name="pet_name" placeholder="宠物名字（如：旺财）" required>
                <textarea name="content" rows="4" placeholder="今天发生了什么？（如：今天拆家了...）" required></textarea>
                <input type="file" name="image" accept="image/*" onchange="previewImage(this, 'petImagePreview')">
                <img id="petImagePreview" class="image-preview" alt="图片预览">
                <button type="submit">提交记录</button>
            </form>
        </div>

        <hr>

        <!-- 宠物记录列表区域 -->
        <div class="list-box">
            <?php if(is_array($logs) || $logs instanceof \think\Collection || $logs instanceof \think\Paginator): $i = 0; $__LIST__ = $logs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$log): $mod = ($i % 2 );++$i;?>
            <div class="log-item">
                <h3>🐾 <?php echo $log['pet_name']; ?></h3>
                <p><?php echo $log['content']; ?></p>
                <?php if(isset($log['image']) && $log['image']): ?>
                <img src="<?php echo $log['image']; ?>" class="post-image" alt="宠物图片">
                <?php endif; ?>
                <div class="time">记录时间：<?php echo $log['create_time']; ?> | 发布者：<?php echo $log['user']['username']; ?></div>
            </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            
            <!-- 宠物记录分页 -->
            <?php if(isset($logs['total']) && $logs['total'] > 5): ?>
            <div class="pagination">
                <?php if($logs['current_page'] > 1): ?>
                <a href="?log_page=<?php echo $logs['current_page']-1; ?>&post_page=<?php echo $posts['current_page']; ?>">上一页</a>
                <?php else: ?>
                <span class="disabled">上一页</span>
                <?php endif; $__FOR_START_122977216__=1;$__FOR_END_122977216__=$logs['last_page'];for($i=$__FOR_START_122977216__;$i < $__FOR_END_122977216__;$i+=1){ if($i == $logs['current_page']): ?>
                <span class="active"><?php echo $i; ?></span>
                <?php else: ?>
                <a href="?log_page=<?php echo $i; ?>&post_page=<?php echo $posts['current_page']; ?>"><?php echo $i; ?></a>
                <?php endif; } if($logs['current_page'] < $logs['last_page']): ?>
                <a href="?log_page=<?php echo $logs['current_page']+1; ?>&post_page=<?php echo $posts['current_page']; ?>">下一页</a>
                <?php else: ?>
                <span class="disabled">下一页</span>
                <?php endif; ?>
                
                <span>共 <?php echo $logs['total']; ?> 条记录</span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- 发表帖子区域 -->
    <div class="section">
        <h2>📝 发表帖子</h2>
        <?php if(session('user_id')): ?>
        <div class="form-box">
            <form action="/post/save" method="post" enctype="multipart/form-data">
                <input type="text" name="title" placeholder="帖子标题" required>
                <textarea name="content" rows="4" placeholder="帖子内容..." required></textarea>
                <input type="file" name="image" accept="image/*" onchange="previewImage(this, 'postImagePreview')">
                <img id="postImagePreview" class="image-preview" alt="图片预览">
                <div class="upload-progress">
                    <div class="upload-progress-bar" id="uploadProgressBar"></div>
                </div>
                <button type="submit">发布帖子</button>
            </form>
        </div>
        <?php else: ?>
        <p style="color: #999;">请先<a href="/login">登录</a>后发表帖子</p>
        <?php endif; ?>
    </div>

    <!-- 帖子展示区域 -->
    <div class="section">
        <h2>📢 社区帖子</h2>
        <div class="post-list" id="postList">
            <?php if(is_array($posts) || $posts instanceof \think\Collection || $posts instanceof \think\Paginator): $i = 0; $__LIST__ = $posts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$post): $mod = ($i % 2 );++$i;?>
            <div class="post-item">
                <div class="post-header">
                    <span class="post-author"><?php echo $post['user']['username']; ?></span>
                    <span class="time"><?php echo $post['created_at']; ?></span>
                </div>
                <div class="post-title"><?php echo $post['title']; ?></div>
                <div class="post-content"><?php echo $post['content']; ?></div>
                <?php if($post['image']): ?>
                <img src="<?php echo $post['image']; ?>" class="post-image" alt="帖子图片">
                <?php endif; ?>
                <div class="post-actions">
                    <button onclick="viewPostDetail(<?php echo $post['id']; ?>)">查看详情</button>
                    <?php if(session('user_id') == $post['user']['id']): ?>
                    <button class="delete-btn" onclick="showDeleteConfirm('post', <?php echo $post['id']; ?>)">删除帖子</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        
        <!-- 帖子分页 -->
        <?php if(isset($posts['total']) && $posts['total'] > 5): ?>
        <div class="pagination">
            <?php if($posts['current_page'] > 1): ?>
            <a href="?post_page=<?php echo $posts['current_page']-1; ?>&log_page=<?php echo $logs['current_page']; ?>">上一页</a>
            <?php else: ?>
            <span class="disabled">上一页</span>
            <?php endif; $__FOR_START_364038859__=1;$__FOR_END_364038859__=$posts['last_page'];for($i=$__FOR_START_364038859__;$i < $__FOR_END_364038859__;$i+=1){ if($i == $posts['current_page']): ?>
            <span class="active"><?php echo $i; ?></span>
            <?php else: ?>
            <a href="?post_page=<?php echo $i; ?>&log_page=<?php echo $logs['current_page']; ?>"><?php echo $i; ?></a>
            <?php endif; } if($posts['current_page'] < $posts['last_page']): ?>
            <a href="?post_page=<?php echo $posts['current_page']+1; ?>&log_page=<?php echo $logs['current_page']; ?>">下一页</a>
            <?php else: ?>
            <span class="disabled">下一页</span>
            <?php endif; ?>
            
            <span>共 <?php echo $posts['total']; ?> 条帖子</span>
        </div>
        <?php endif; ?>
    </div>

    <script>
        // 切换用户下拉菜单
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // 点击其他地方关闭下拉菜单
        document.addEventListener('click', function(event) {
            const userInfo = document.querySelector('.user-info');
            const dropdown = document.getElementById('userDropdown');
            
            if (event.target.tagName === 'A') {
                dropdown.classList.remove('show');
                return;
            }

            if (!userInfo.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // 查看帖子详情
        function viewPostDetail(postId) {
            window.location.href = '/post/detail/' + postId;
        }

        // 图片预览
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.add('show');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.remove('show');
            }
        }

        // 删除相关变量
        let deleteType = '';
        let deleteId = 0;

        // 显示删除确认弹窗
        function showDeleteConfirm(type, id) {
            deleteType = type;
            deleteId = id;
            const message = type === 'post' ? '确定要删除这篇帖子吗？' : '确定要删除这条评论吗？';
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('confirmModal').classList.add('show');
        }

        // 确认删除
        function confirmDelete() {
            if (deleteType === 'post') {
                window.location.href = '/post/delete/' + deleteId;
            } else if (deleteType === 'comment') {
                window.location.href = '/comment/delete/' + deleteId;
            }
            document.getElementById('confirmModal').classList.remove('show');
        }

        // 取消删除
        function cancelDelete() {
            document.getElementById('confirmModal').classList.remove('show');
            deleteType = '';
            deleteId = 0;
        }
    </script>
</body>
</html>