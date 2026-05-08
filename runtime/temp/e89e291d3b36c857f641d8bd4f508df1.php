<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"C:\Users\EDY\tp5\public/../application/index\view\post\detail.html";i:1778231570;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <title>帖子详情</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { margin: 0; }
        .back-link { color: #ff9900; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .user-info { position: relative; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: #ff9900; color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; }
        .user-dropdown { position: absolute; top: 50px; right: 0; background: white; border: 1px solid #ddd; border-radius: 8px; padding: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 100; display: none; }
        .user-dropdown.show { display: block; }
        .user-dropdown a { display: block; padding: 8px 16px; text-decoration: none; color: #333; }
        .user-dropdown a:hover { background: #f5f5f5; }
        .post-item { border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 30px; }
        .post-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .post-author { font-weight: bold; }
        .post-title { font-size: 24px; font-weight: bold; margin-bottom: 15px; }
        .post-content { line-height: 1.8; margin-bottom: 20px; }
        .post-image { max-width: 100%; height: auto; border-radius: 4px; margin-bottom: 15px; }
        .comment-section { margin-top: 40px; }
        .comment-section h3 { margin-bottom: 20px; }
        .comment-form { background: #f0f0f0; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .comment-form textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 10px; }
        .comment-form button { background: #ff9900; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; }
        .comment-form button:hover { background: #e68a00; }
        .comment-item { border-left: 3px solid #ff9900; padding-left: 15px; margin-bottom: 20px; }
        .comment-header { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 8px; }
        .comment-author { font-weight: bold; }
        .comment-time { color: #999; font-size: 12px; }
        .comment-content { line-height: 1.5; }
        .comment-image { max-width: 150px; height: auto; border-radius: 4px; margin-top: 10px; }
        .delete-btn { background: #e74c3c !important; }
        .delete-btn:hover { background: #c0392b !important; }
        .load-more { text-align: center; margin: 20px 0; }
        .load-more button { background: #f5f5f5; color: #333; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; }
        .load-more button:hover { background: #e0e0e0; }
        .no-comments { text-align: center; color: #999; padding: 30px; }
        
        /* 图片预览 */
        .image-preview { margin: 10px 0; max-width: 150px; max-height: 150px; display: none; }
        .image-preview.show { display: block; }
        
        /* 弹窗样式 */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000; }
        .modal.show { display: flex; }
        .modal-content { background: white; padding: 20px; border-radius: 8px; text-align: center; min-width: 300px; }
        .modal-buttons { display: flex; gap: 10px; justify-content: center; margin-top: 20px; }
        .modal-buttons button { padding: 8px 20px; }
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
        <h1>🐶 帖子详情</h1>
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

    <!-- 返回主页链接 -->
    <p><a href="/index" class="back-link">← 返回主页</a></p>

    <!-- 帖子内容 -->
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
    </div>

    <!-- 评论区域 -->
    <div class="comment-section">
        <h3>💬 评论 (<?php echo $commentCount; ?>)</h3>
        
        <!-- 发表评论表单 -->
        <?php if(session('user_id')): ?>
        <div class="comment-form">
            <form action="/comment/save" method="post" enctype="multipart/form-data">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <input type="hidden" name="parent_id" value="0">
                <textarea name="content" rows="4" placeholder="写下你的评论..." required></textarea>
                <input type="file" name="image" accept="image/*" onchange="previewImage(this, 'commentImagePreview')">
                <img id="commentImagePreview" class="image-preview" alt="图片预览">
                <button type="submit">发表评论</button>
            </form>
        </div>
        <?php else: ?>
        <p style="color: #999;">请先<a href="/login">登录</a>后发表评论</p>
        <?php endif; ?>

        <!-- 评论列表 -->
        <div class="comment-list" id="commentList">
            <?php if(count($comments) > 0): if(is_array($comments) || $comments instanceof \think\Collection || $comments instanceof \think\Paginator): $i = 0; $__LIST__ = $comments;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$comment): $mod = ($i % 2 );++$i;?>
                <div class="comment-item">
                    <div class="comment-header">
                        <span class="comment-author"><?php echo $comment['user']['username']; ?></span>
                        <span class="comment-time"><?php echo $comment['created_at']; ?></span>
                        <?php if(session('user_id') == $comment['user']['id']): ?>
                        <button class="delete-btn" onclick="showDeleteConfirm('comment', <?php echo $comment['id']; ?>)">删除</button>
                        <?php endif; ?>
                    </div>
                    <div class="comment-content"><?php echo $comment['content']; ?></div>
                    <?php if($comment['image']): ?>
                    <img src="<?php echo $comment['image']; ?>" class="comment-image" alt="评论图片">
                    <?php endif; ?>
                </div>
                <?php endforeach; endif; else: echo "" ;endif; else: ?>
                <div class="no-comments">暂无评论，快来发表第一条评论吧！</div>
            <?php endif; ?>
        </div>

        <?php if(count($comments) >= 10): ?>
        <div class="load-more">
            <button onclick="loadMoreComments(<?php echo $post['id']; ?>)">加载更多评论</button>
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

        // 加载更多评论
        function loadMoreComments(postId) {
            const commentList = document.getElementById('commentList');
            const loadMoreBtn = document.querySelector('.load-more button');
            
            loadMoreBtn.innerHTML = '加载中...';
            
            setTimeout(() => {
                commentList.innerHTML += `
                    <div class="comment-item">
                        <div class="comment-header">
                            <span class="comment-author">用户测试</span>
                            <span class="comment-time">2026-04-23 10:00:00</span>
                        </div>
                        <div class="comment-content">这是一条加载的评论</div>
                    </div>
                `;
                loadMoreBtn.innerHTML = '加载更多评论';
            }, 500);
        }
    </script>
</body>
</html>