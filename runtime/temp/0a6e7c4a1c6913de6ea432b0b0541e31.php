<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:64:"C:\Users\EDY\tp5\public/../application/index\view\pet\index.html";i:1778553401;}*/ ?>
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
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: #ff9900; color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; overflow: hidden; flex-shrink: 0; }
        .user-avatar.has-photo { background: transparent; color: transparent; font-size: 0; }
        .user-avatar.has-photo img { width: 100%; height: 100%; object-fit: cover; display: block; border-radius: 50%; border: 2px solid #ff9900; }
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
        .log-header { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
        .log-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #ff9900; }
        .log-avatar-placeholder { width: 40px; height: 40px; border-radius: 50%; background: #ff9900; color: white; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: bold; }
        .log-meta { flex: 1; }
        .log-pet-name { font-size: 16px; font-weight: bold; color: #333; }
        .log-username { font-size: 12px; color: #666; }
        .log-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; }
        .log-footer .time { color: #999; font-size: 12px; }
        .log-footer .delete-btn { padding: 5px 15px; font-size: 12px; }
        .section { margin-bottom: 40px; }
        .section h2 { margin-bottom: 20px; }
        .post-item { border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .post-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .post-author-info { display: flex; align-items: center; gap: 10px; }
        .post-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #ff9900; }
        .post-avatar-placeholder { width: 40px; height: 40px; border-radius: 50%; background: #ff9900; color: white; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: bold; }
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
        
        /* 互动按钮样式 */
        .interaction-bar { display: flex; gap: 15px; margin-top: 10px; }
        .interaction-btn { display: flex; align-items: center; gap: 4px; padding: 4px 10px; border: 1px solid #eee; border-radius: 15px; background: white; cursor: pointer; font-size: 13px; color: #666; transition: all 0.3s; }
        .interaction-btn:hover { background: #f9f9f9; border-color: #ddd; }
        .interaction-btn.active-like { color: #e74c3c; border-color: #e74c3c; background: #fff5f5; }
        .interaction-btn.active-bookmark { color: #f39c12; border-color: #f39c12; background: #fffdf5; }
        .interaction-btn .count { font-weight: bold; }
        
        /* 礼物弹窗 */
        .gift-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 2000; }
        .gift-modal.show { display: flex; }
        .gift-modal-content { background: white; padding: 25px; border-radius: 12px; width: 90%; max-width: 400px; }
        .gift-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin: 15px 0; }
        .gift-option { padding: 15px; border: 2px solid #eee; border-radius: 8px; text-align: center; cursor: pointer; transition: all 0.3s; }
        .gift-option:hover { border-color: #ff9900; background: #fff8f0; }
        .gift-option.selected { border-color: #ff9900; background: #fff8f0; }
        .gift-option .icon { font-size: 28px; }
        .gift-option .name { font-size: 14px; font-weight: bold; margin-top: 5px; }
        .gift-option .price { font-size: 12px; color: #999; margin-top: 3px; }
    </style>
    <script>window.PAGE_CSRF = '<?php echo (isset($csrf_token) && ($csrf_token !== '')?$csrf_token:""); ?>';</script>
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
        <h1>🐶 铲屎官记录本<?php if(isset($can_moderate_all) && $can_moderate_all): ?> <span style="font-size:14px;color:#c0392b;">(管理)</span><?php endif; ?></h1>
        <div class="user-info">
            <div class="user-avatar<?php if(isset($current_avatar) && $current_avatar): ?> has-photo<?php endif; ?>" onclick="toggleUserDropdown()">
                <?php if(isset($current_avatar) && $current_avatar): ?>
                <img src="<?php echo $current_avatar; ?>" alt="" width="40" height="40" loading="eager">
                <?php else: if(session('username')): ?>
                <?php echo substr(\think\Session::get('username'),0,1); else: ?>
                登
                <?php endif; endif; ?>
            </div>
            <div class="user-dropdown" id="userDropdown">
                <?php if(session('user_id')): ?>
                <a href="/profile">个人信息</a>
                <a href="/pet_profile">我的宠物档案</a>
                <a href="/achievement">等级成就</a>
                <a href="/message">💬 消息中心</a>
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
        <?php if(session('user_id')): ?>
        <div class="form-box">
            <form action="/pet/save" method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo (isset($csrf_token) && ($csrf_token !== '')?$csrf_token:''); ?>">
                <input type="text" name="pet_name" placeholder="宠物名字（如：旺财）" required>
                <textarea name="content" rows="4" placeholder="今天发生了什么？（如：今天拆家了...）" required></textarea>
                <input type="file" name="image" accept="image/*" onchange="previewImage(this, 'petImagePreview')">
                <img id="petImagePreview" class="image-preview" alt="图片预览">
                <button type="submit">提交记录</button>
            </form>
        </div>
        
        <?php else: ?>
        <p style="color: #999;">请先<a href="/login">登录</a>后记录宠物生活</p>
        <?php endif; ?>

        <hr>

        <!-- 宠物记录列表区域 -->
        <div class="list-box" id="petLogList">
            <?php if(is_array($logs) || $logs instanceof \think\Collection || $logs instanceof \think\Paginator): $i = 0; $__LIST__ = $logs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$log): $mod = ($i % 2 );++$i;?>
            <div class="log-item" id="log-item-<?php echo $log['id']; ?>">
                <div class="log-header">
                    <?php if(isset($users[$log['user_id']]) && isset($users[$log['user_id']]['avatar']) && $users[$log['user_id']]['avatar']): ?>
                    <img src="<?php echo $users[$log['user_id']]['avatar']; ?>" alt="<?php echo $users[$log['user_id']]['username']; ?>" class="log-avatar" loading="lazy">
                    <?php else: ?>
                    <div class="log-avatar-placeholder"><?php if(isset($users[$log['user_id']])): ?><?php echo strtoupper(substr($users[$log['user_id']]['username'],0,1)); else: ?>U<?php endif; ?></div>
                    <?php endif; ?>
                    <div class="log-meta">
                        <div class="log-pet-name">🐾 <?php echo $log['pet_name']; ?></div>
                        <div class="log-username"><?php if(isset($users[$log['user_id']])): ?><?php echo $users[$log['user_id']]['username']; else: ?>未知用户<?php endif; ?></div>
                    </div>
                </div>
                <p><?php echo $log['content']; ?></p>
                <?php if(isset($log['image']) && $log['image']): ?>
                <img src="<?php echo $log['image']; ?>" class="post-image" alt="宠物图片">
                <?php endif; ?>
                <div class="interaction-bar">
                    <button class="interaction-btn" onclick="toggleLike('pet_log', <?php echo $log['id']; ?>, this)" data-liked="false">
                        <span>❤️</span>
                        <span class="count">0</span>
                    </button>
                </div>
                <div class="log-footer">
                    <div class="time"><?php echo $log['create_time']; ?></div>
                    <?php if(session('user_id') == $log['user_id'] || (isset($can_moderate_all) && $can_moderate_all)): ?>
                    <button class="delete-btn" onclick="deletePetLogItem(<?php echo $log['id']; ?>)">删除</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            
            <!-- 宠物记录分页 -->
            <?php if(isset($logs['total']) && $logs['total'] > 5): ?>
            <div class="pagination">
                <?php if($logs['current_page'] > 1): ?>
                <a href="?log_page=<?php echo $logs['current_page']-1; ?>&post_page=<?php echo $posts['current_page']; ?>">上一页</a>
                <?php else: ?>
                <span class="disabled">上一页</span>
                <?php endif; $__FOR_START_1758065871__=1;$__FOR_END_1758065871__=$logs['last_page'];for($i=$__FOR_START_1758065871__;$i < $__FOR_END_1758065871__;$i+=1){ if($i == $logs['current_page']): ?>
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
                <input type="hidden" name="csrf_token" value="<?php echo (isset($csrf_token) && ($csrf_token !== '')?$csrf_token:''); ?>">
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
                    <div class="post-author-info">
                        <?php if(isset($users[$post['user_id']]) && isset($users[$post['user_id']]['avatar']) && $users[$post['user_id']]['avatar']): ?>
                        <img src="<?php echo $users[$post['user_id']]['avatar']; ?>" alt="<?php echo $users[$post['user_id']]['username']; ?>" class="post-avatar" loading="lazy">
                        <?php else: ?>
                        <div class="post-avatar-placeholder"><?php if(isset($users[$post['user_id']])): ?><?php echo strtoupper(substr($users[$post['user_id']]['username'],0,1)); else: ?>U<?php endif; ?></div>
                        <?php endif; ?>
                        <span class="post-author"><?php if(isset($users[$post['user_id']])): ?><?php echo $users[$post['user_id']]['username']; else: ?>未知用户<?php endif; ?></span>
                    </div>
                    <span class="time"><?php echo $post['created_at']; ?></span>
                </div>
                <div class="post-title"><?php echo $post['title']; ?></div>
                <div class="post-content"><?php echo $post['content']; ?></div>
                <?php if($post['image']): ?>
                <img src="<?php echo $post['image']; ?>" class="post-image" alt="帖子图片">
                <?php endif; ?>
                <div class="interaction-bar">
                    <button class="interaction-btn" onclick="toggleLike('post', <?php echo $post['id']; ?>, this)" data-liked="false">
                        <span>❤️</span>
                        <span class="count">0</span>
                    </button>
                    <button class="interaction-btn" onclick="toggleBookmark('post', <?php echo $post['id']; ?>, this)" data-bookmarked="false">
                        <span>⭐</span>
                    </button>
                    <button class="interaction-btn" onclick="openGiftModal(<?php echo $post['user_id']; ?>, 'post', <?php echo $post['id']; ?>)">
                        <span>🎁</span>
                    </button>
                </div>
                <div class="post-actions">
                    <button onclick="viewPostDetail(<?php echo $post['id']; ?>)">查看详情</button>
                    <?php if(session('user_id') == $post['user_id'] || (isset($can_moderate_all) && $can_moderate_all)): ?>
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
            <?php endif; $__FOR_START_1886224878__=1;$__FOR_END_1886224878__=$posts['last_page'];for($i=$__FOR_START_1886224878__;$i < $__FOR_END_1886224878__;$i+=1){ if($i == $posts['current_page']): ?>
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
            var tok = encodeURIComponent(window.PAGE_CSRF || '');
            if (deleteType === 'post') {
                window.location.href = '/post/delete/' + deleteId + '?csrf=' + tok;
            } else if (deleteType === 'comment') {
                window.location.href = '/comment/delete/' + deleteId + '?csrf=' + tok;
            }
            document.getElementById('confirmModal').classList.remove('show');
        }

        // 取消删除
        function cancelDelete() {
            document.getElementById('confirmModal').classList.remove('show');
            deleteType = '';
            deleteId = 0;
        }

        // 删除宠物记录项（带确认弹窗和局部更新）
        function deletePetLogItem(logId) {
            // 二次确认
            if (!confirm('⚠️ 警告：删除后无法恢复！\n\n确定要删除这条宠物记录吗？')) {
                return;
            }
            
            // 获取记录项元素
            const logItem = document.getElementById('log-item-' + logId);
            if (!logItem) {
                alert('记录不存在');
                return;
            }
            
            // 显示删除中状态
            logItem.style.opacity = '0.5';
            
            // 发送DELETE请求
            fetch('/pet/log/' + logId, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'X-Csrf-Token': window.PAGE_CSRF || ''
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.code === 200) {
                    // 删除成功，局部更新页面（移除该记录项）
                    logItem.style.transition = 'all 0.3s ease';
                    logItem.style.opacity = '0';
                    logItem.style.transform = 'translateX(-100%)';
                    
                    setTimeout(() => {
                        logItem.remove();
                        
                        // 检查是否还有记录
                        const remainingItems = document.querySelectorAll('.log-item');
                        if (remainingItems.length === 0) {
                            // 如果没有记录了，显示空提示
                            const listBox = document.getElementById('petLogList');
                            listBox.innerHTML = '<div style="text-align: center; color: #999; padding: 30px;">暂无宠物记录，快来发布第一条吧！</div>';
                        }
                    }, 300);
                    
                    // 显示成功提示（可选）
                    showToast(data.msg || '删除成功');
                } else {
                    // 删除失败，恢复样式
                    logItem.style.opacity = '1';
                    alert(data.msg || '删除失败');
                }
            })
            .catch(error => {
                // 网络错误或其他异常
                logItem.style.opacity = '1';
                console.error('Error:', error);
                alert('删除失败，请检查网络连接或稍后重试');
            });
        }
        
        // 显示提示消息
        function showToast(message) {
            // 创建提示元素
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: #27ae60;
                color: white;
                padding: 12px 24px;
                border-radius: 4px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                z-index: 9999;
                font-size: 14px;
                opacity: 0;
                transition: opacity 0.3s ease;
            `;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            // 显示动画
            setTimeout(() => {
                toast.style.opacity = '1';
            }, 10);
            
            // 自动隐藏
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 2000);
        }
        
        // 图片懒加载
        function initLazyLoad() {
            // 检查浏览器是否支持 IntersectionObserver
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            // 添加加载动画
                            img.style.opacity = '0';
                            img.style.transition = 'opacity 0.3s ease';
                            
                            // 加载图片
                            const tempImg = new Image();
                            tempImg.onload = function() {
                                img.src = tempImg.src;
                                img.style.opacity = '1';
                            };
                            tempImg.onerror = function() {
                                // 加载失败时显示占位符
                                img.style.display = 'none';
                                const placeholder = img.nextElementSibling;
                                if (placeholder && placeholder.classList.contains('log-avatar-placeholder')) {
                                    placeholder.style.display = 'flex';
                                }
                            };
                            tempImg.src = img.dataset.src || img.src;
                            
                            observer.unobserve(img);
                        }
                    });
                }, {
                    rootMargin: '50px 0px', // 提前50px开始加载
                    threshold: 0.01
                });
                
                // 观察所有需要懒加载的图片
                document.querySelectorAll('img[loading="lazy"]').forEach(img => {
                    imageObserver.observe(img);
                });
            } else {
                // 浏览器不支持 IntersectionObserver，直接加载所有图片
                document.querySelectorAll('img[loading="lazy"]').forEach(img => {
                    img.style.opacity = '1';
                });
            }
        }
        
        // 页面加载完成后初始化懒加载
        document.addEventListener('DOMContentLoaded', initLazyLoad);
        
        // ========== 互动功能 ==========
        
        // 礼物弹窗状态
        let giftToUserId = 0;
        let giftTargetType = '';
        let giftTargetId = 0;
        let selectedGiftType = '';
        
        // 点赞/取消点赞
        function toggleLike(targetType, targetId, btn) {
            var userId = '<?php echo (\think\Session::get('user_id') ?: ""); ?>';
            if (!userId) {
                showToast('请先登录');
                return;
            }
            
            fetch('/interaction/like_toggle', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'target_type=' + targetType + '&target_id=' + targetId
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.code === 200) {
                    var countEl = btn.querySelector('.count');
                    if (data.liked) {
                        btn.classList.add('active-like');
                        btn.dataset.liked = 'true';
                    } else {
                        btn.classList.remove('active-like');
                        btn.dataset.liked = 'false';
                    }
                    if (countEl) {
                        countEl.textContent = data.count;
                    }
                } else {
                    showToast(data.msg || '操作失败');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                showToast('网络错误');
            });
        }
        
        // 收藏/取消收藏
        function toggleBookmark(targetType, targetId, btn) {
            var userId = '<?php echo (\think\Session::get('user_id') ?: ""); ?>';
            if (!userId) {
                showToast('请先登录');
                return;
            }
            
            fetch('/interaction/bookmark_toggle', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'target_type=' + targetType + '&target_id=' + targetId + '&folder=默认收藏'
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.code === 200) {
                    if (data.bookmarked) {
                        btn.classList.add('active-bookmark');
                        btn.dataset.bookmarked = 'true';
                    } else {
                        btn.classList.remove('active-bookmark');
                        btn.dataset.bookmarked = 'false';
                    }
                    showToast(data.msg);
                } else {
                    showToast(data.msg || '操作失败');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                showToast('网络错误');
            });
        }
        
        // 打开送礼弹窗
        function openGiftModal(toUserId, targetType, targetId) {
            var userId = '<?php echo (\think\Session::get('user_id') ?: ""); ?>';
            if (!userId) {
                showToast('请先登录');
                return;
            }
            
            giftToUserId = toUserId;
            giftTargetType = targetType;
            giftTargetId = targetId;
            selectedGiftType = '';
            
            document.getElementById('sendGiftBtn').disabled = true;
            document.getElementById('giftMessage').value = '';
            
            fetch('/interaction/gift_types')
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.code === 200) {
                        var grid = document.getElementById('giftGrid');
                        grid.innerHTML = '';
                        data.data.forEach(function(gift) {
                            var div = document.createElement('div');
                            div.className = 'gift-option';
                            div.onclick = function() { selectGift(gift.type); };
                            div.innerHTML = '<div class="icon">' + gift.icon + '</div><div class="name">' + gift.name + '</div><div class="price">' + gift.price + ' 积分</div>';
                            grid.appendChild(div);
                        });
                        document.getElementById('giftModal').classList.add('show');
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    showToast('加载礼物列表失败');
                });
        }
        
        // 选择礼物
        function selectGift(giftType) {
            selectedGiftType = giftType;
            document.querySelectorAll('.gift-option').forEach(function(el) {
                el.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            document.getElementById('sendGiftBtn').disabled = false;
        }
        
        // 关闭送礼弹窗
        function closeGiftModal() {
            document.getElementById('giftModal').classList.remove('show');
        }
        
        // 确认送出礼物
        function confirmSendGift() {
            if (!selectedGiftType) {
                showToast('请选择一个礼物');
                return;
            }
            
            var message = document.getElementById('giftMessage').value;
            
            fetch('/interaction/send_gift', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'to_user_id=' + giftToUserId + '&gift_type=' + selectedGiftType + '&message=' + encodeURIComponent(message) + '&target_type=' + giftTargetType + '&target_id=' + giftTargetId
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.code === 200) {
                    showToast('🎁 礼物已送出！');
                    closeGiftModal();
                } else {
                    showToast(data.msg || '送礼失败');
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                showToast('网络错误');
            });
        }
        
        // 覆盖 showToast，统一提示风格
        var originalShowToast = window.showToast || function(msg) {
            var toast = document.createElement('div');
            toast.style.cssText = 'position:fixed;top:20px;left:50%;transform:translateX(-50%);background:#27ae60;color:white;padding:12px 24px;border-radius:4px;box-shadow:0 2px 10px rgba(0,0,0,0.2);z-index:9999;font-size:14px;opacity:0;transition:opacity 0.3s ease;';
            toast.textContent = msg;
            document.body.appendChild(toast);
            setTimeout(function() { toast.style.opacity = '1'; }, 10);
            setTimeout(function() { toast.style.opacity = '0'; setTimeout(function() { toast.remove(); }, 300); }, 2000);
        };
        window.showToast = originalShowToast;
    </script>

    <!-- 礼物弹窗 -->
    <div class="gift-modal" id="giftModal">
        <div class="gift-modal-content">
            <h3 style="text-align:center;margin-bottom:15px;">🎁 选择礼物</h3>
            <div class="gift-grid" id="giftGrid"></div>
            <div style="margin:10px 0;">
                <label style="font-weight:bold;">留言（可选）</label>
                <input type="text" id="giftMessage" placeholder="发送祝福给TA..." style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;margin-top:5px;">
            </div>
            <div style="display:flex;gap:10px;justify-content:center;">
                <button onclick="closeGiftModal()" style="background:#f0f0f0;color:#333;border:none;padding:10px 25px;border-radius:6px;cursor:pointer;">取消</button>
                <button onclick="confirmSendGift()" id="sendGiftBtn" style="background:#ff9900;color:white;border:none;padding:10px 25px;border-radius:6px;cursor:pointer;" disabled>确认送出</button>
            </div>
        </div>
    </div>
</body>
</html>