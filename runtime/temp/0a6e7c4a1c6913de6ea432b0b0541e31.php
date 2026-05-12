<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:64:"C:\Users\EDY\tp5\public/../application/index\view\pet\index.html";i:1778566400;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <title>宠物生活记录</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; max-width: 900px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #fef9f3 0%, #fff8f0 100%); min-height: 100vh; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding: 20px; background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header h1 { margin: 0; font-size: 24px; color: #333; }
        
        .header-left { display: flex; align-items: center; gap: 12px; }
        .quick-actions { display: flex; gap: 10px; }
        .quick-btn { display: flex; align-items: center; gap: 6px; padding: 10px 18px; background: linear-gradient(135deg, #ff9900, #ff6600); color: white; border: none; border-radius: 25px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 12px rgba(255,153,0,0.3); }
        .quick-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,153,0,0.4); }
        .quick-btn:active { transform: translateY(0); }
        .quick-btn.secondary { background: linear-gradient(135deg, #3498db, #2980b9); box-shadow: 0 4px 12px rgba(52,152,219,0.3); }
        .quick-btn.secondary:hover { box-shadow: 0 6px 20px rgba(52,152,219,0.4); }
        
        .header-center { display: flex; align-items: center; gap: 15px; }
        
        .user-area { display: flex; align-items: center; gap: 12px; }
        .message-btn { position: relative; width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; font-size: 20px; transition: all 0.3s; box-shadow: 0 4px 12px rgba(231,76,60,0.3); }
        .message-btn:hover { transform: scale(1.1); box-shadow: 0 6px 20px rgba(231,76,60,0.4); }
        .message-badge { position: absolute; top: -4px; right: -4px; background: #e74c3c; color: white; font-size: 11px; padding: 2px 6px; border-radius: 10px; font-weight: bold; min-width: 18px; text-align: center; }
        
        .user-avatar { width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, #ff9900, #ff6600); color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; overflow: hidden; flex-shrink: 0; font-size: 18px; font-weight: bold; transition: all 0.3s; box-shadow: 0 4px 12px rgba(255,153,0,0.3); position: relative; }
        .user-avatar:hover { transform: scale(1.05); }
        .user-avatar.has-photo { background: transparent; color: transparent; font-size: 0; }
        .user-avatar.has-photo img { width: 100%; height: 100%; object-fit: cover; display: block; border-radius: 50%; border: 3px solid #ff9900; }
        
        .user-dropdown { position: absolute; top: 100%; right: 0; background: white; border: 1px solid #eee; border-radius: 12px; padding: 10px 0; box-shadow: 0 8px 30px rgba(0,0,0,0.12); z-index: 100; display: none; min-width: 180px; margin-top: 8px; }
        .user-dropdown.show { display: block; animation: dropdownFadeIn 0.3s ease; }
        @keyframes dropdownFadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .user-dropdown a { display: flex; align-items: center; gap: 8px; padding: 12px 20px; text-decoration: none; color: #555; font-size: 14px; transition: background 0.2s; }
        .user-dropdown a:hover { background: #fef9f3; color: #ff9900; }
        
        .main-content { display: grid; grid-template-columns: 1fr 340px; gap: 24px; }
        @media (max-width: 800px) { .main-content { grid-template-columns: 1fr; } }
        
        .section { margin-bottom: 30px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .section h2 { margin: 0; font-size: 20px; color: #333; display: flex; align-items: center; gap: 8px; }
        .section-badge { background: linear-gradient(135deg, #ff9900, #ff6600); color: white; font-size: 12px; padding: 4px 12px; border-radius: 12px; }
        
        .square-card { background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); margin-bottom: 20px; transition: all 0.3s; }
        .square-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.1); transform: translateY(-2px); }
        
        .pet-record { display: flex; gap: 16px; padding: 20px 0; border-bottom: 1px solid #f0f0f0; }
        .pet-record:last-child { border-bottom: none; padding-bottom: 0; }
        .pet-avatar-small { width: 56px; height: 56px; border-radius: 50%; object-fit: cover; border: 3px solid #ff9900; flex-shrink: 0; }
        .pet-avatar-placeholder { width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, #ff9900, #ff6600); color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold; flex-shrink: 0; }
        .pet-info { flex: 1; min-width: 0; }
        .pet-name { font-size: 17px; font-weight: bold; color: #333; margin-bottom: 6px; display: flex; align-items: center; gap: 6px; }
        .pet-name-icon { color: #ff9900; }
        .pet-owner { font-size: 13px; color: #888; margin-bottom: 10px; }
        .pet-content { font-size: 15px; color: #555; line-height: 1.7; margin-bottom: 12px; }
        .pet-meta { display: flex; gap: 12px; font-size: 13px; color: #999; }
        .pet-image { width: 100%; max-height: 300px; object-fit: cover; border-radius: 12px; margin-top: 12px; }
        
        .pagination { display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 24px; padding-top: 20px; border-top: 1px solid #f0f0f0; }
        .pagination a, .pagination span { padding: 10px 16px; border: 1px solid #ddd; border-radius: 8px; text-decoration: none; color: #555; font-size: 14px; transition: all 0.2s; }
        .pagination a:hover { background: #fff8f0; border-color: #ff9900; color: #ff9900; }
        .pagination .active { background: linear-gradient(135deg, #ff9900, #ff6600); color: white; border-color: #ff9900; }
        .pagination .disabled { opacity: 0.4; cursor: not-allowed; }
        .pagination .disabled:hover { background: none; border-color: #ddd; color: #555; }
        
        .empty-state { text-align: center; padding: 60px 20px; color: #aaa; }
        .empty-state-icon { font-size: 60px; margin-bottom: 15px; }
        
        /* 上传按钮美化 */
        .upload-label { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; background: linear-gradient(135deg, #3498db, #2980b9); color: white; border-radius: 8px; cursor: pointer; font-size: 14px; transition: all 0.3s; box-shadow: 0 4px 12px rgba(52,152,219,0.3); }
        .upload-label:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(52,152,219,0.4); }
        .upload-label:active { transform: translateY(0); }
        input[type="file"] { display: none; }
        
        .delete-btn { background: linear-gradient(135deg, #e74c3c, #c0392b) !important; box-shadow: 0 4px 15px rgba(231,76,60,0.3) !important; }
        .delete-btn:hover { box-shadow: 0 6px 20px rgba(231,76,60,0.4) !important; }
        button:disabled { opacity: 0.5; cursor: not-allowed; transform: none !important; }
        
        .interaction-bar { display: flex; gap: 12px; margin-top: 14px; padding-top: 14px; border-top: 1px solid #f5f5f5; }
        .interaction-btn { display: flex; align-items: center; gap: 5px; padding: 8px 14px; border: 1px solid #eee; border-radius: 20px; background: white; cursor: pointer; font-size: 13px; color: #666; transition: all 0.3s; }
        .interaction-btn:hover { background: #fef9f3; border-color: #ffd9b3; }
        .interaction-btn.active-like { color: #e74c3c; border-color: #e74c3c; background: #fff5f5; }
        .interaction-btn.active-bookmark { color: #f39c12; border-color: #f39c12; background: #fffdf5; }
        .interaction-btn .count { font-weight: bold; }
        
        .gift-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 2000; }
        .gift-modal.show { display: flex; }
        .gift-modal-content { background: white; padding: 28px; border-radius: 16px; width: 90%; max-width: 420px; }
        .gift-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin: 20px 0; }
        .gift-option { padding: 16px; border: 2px solid #eee; border-radius: 12px; text-align: center; cursor: pointer; transition: all 0.3s; }
        .gift-option:hover { border-color: #ff9900; background: #fff8f0; transform: scale(1.02); }
        .gift-option.selected { border-color: #ff9900; background: linear-gradient(135deg, #fff8f0, #fff0e0); }
        .gift-option .icon { font-size: 32px; }
        .gift-option .name { font-size: 14px; font-weight: bold; margin-top: 6px; color: #333; }
        .gift-option .price { font-size: 12px; color: #999; margin-top: 4px; }
        
        .toast { position: fixed; top: 20px; left: 50%; transform: translateX(-50%) translateY(-100px); background: linear-gradient(135deg, #27ae60, #2ecc71); color: white; padding: 14px 28px; border-radius: 10px; box-shadow: 0 8px 25px rgba(39,174,96,0.4); z-index: 9999; opacity: 0; transition: all 0.4s ease; font-size: 15px; }
        .toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }
        .toast.error { background: linear-gradient(135deg, #e74c3c, #c0392b); box-shadow: 0 8px 25px rgba(231,76,60,0.4); }
        
        @media (max-width: 600px) {
            body { padding: 12px; }
            .header { flex-direction: column; gap: 15px; padding: 16px; }
            .header h1 { font-size: 20px; }
            .quick-actions { width: 100%; }
            .quick-btn { flex: 1; justify-content: center; font-size: 13px; padding: 10px 12px; }
            .user-area { width: 100%; justify-content: flex-end; }
            .section h2 { font-size: 18px; }
            .form-row { flex-direction: column; }
            .form-actions { flex-direction: column; }
            button[type="submit"] { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="toast" id="toast"></div>

    <div class="gift-modal" id="giftModal">
        <div class="gift-modal-content">
            <h3 style="text-align:center;margin-bottom:5px;">🎁 选择礼物</h3>
            <div class="gift-grid" id="giftGrid"></div>
            <div style="margin:12px 0;">
                <input type="text" id="giftMessage" placeholder="发送祝福给TA..." style="width:100%;padding:12px;border:2px solid #eee;border-radius:8px;font-size:14px;">
            </div>
            <div style="display:flex;gap:12px;justify-content:center;">
                <button onclick="closeGiftModal()" style="background:#f5f5f5;color:#555;border:none;padding:12px 24px;border-radius:8px;cursor:pointer;font-size:14px;">取消</button>
                <button onclick="confirmSendGift()" id="sendGiftBtn" style="background:#ccc;color:white;border:none;padding:12px 24px;border-radius:8px;cursor:not-allowed;font-size:14px;" disabled>确认送出</button>
            </div>
        </div>
    </div>

    <div class="header">
        <h1>🐾 铲屎官记录本</h1>
        
        <div class="header-center">
            <?php if(session('user_id')): ?>
            <div class="quick-actions">
                <button class="quick-btn" onclick="location.href='/pet/create'">
                    � 记录宠物
                </button>
                <button class="quick-btn secondary" onclick="location.href='/post/create'">
                    📮 发布帖子
                </button>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="user-area">
            <?php if(session('user_id')): ?>
            <button class="message-btn" onclick="location.href='/message'" title="消息中心">
                💬
                <span class="message-badge" id="msgBadge" style="display:none;">0</span>
            </button>
            <?php endif; ?>
            
            <div class="user-avatar<?php if(isset($current_avatar) && $current_avatar): ?> has-photo<?php endif; ?>" onclick="toggleUserDropdown()">
                <?php if(isset($current_avatar) && $current_avatar): ?>
                <img src="<?php echo $current_avatar; ?>" alt="" width="44" height="44">
                <?php else: if(session('username')): ?>
                <?php echo substr(\think\Session::get('username'),0,1); else: ?>
                登
                <?php endif; endif; ?>
                <div class="user-dropdown" id="userDropdown">
                    <?php if(session('user_id')): ?>
                    <a href="/profile">👤 个人信息</a>
                    <a href="/pet_profile">🐾 我的宠物档案</a>
                    <a href="/achievement">🏆 等级成就</a>
                    <a href="/logout">🚪 退出登录</a>
                    <?php else: ?>
                    <a href="/login">🔑 登录</a>
                    <a href="/register">📝 注册</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="left-content">
            <?php if(session('user_id')): else: ?>
            <div class="square-card" style="text-align:center;padding:40px;">
                <div style="font-size:48px;margin-bottom:15px;">🐾</div>
                <p style="color:#888;margin:0 0 20px;">登录后可记录宠物生活</p>
                <a href="/login" style="display:inline-block;padding:12px 32px;background:linear-gradient(135deg,#ff9900,#ff6600);color:white;text-decoration:none;border-radius:25px;font-weight:600;">立即登录</a>
            </div>
            <?php endif; ?>
            <div class="section">
                <div class="section-header">
                    <h2>📢 社区帖子 <span class="section-badge"><?php echo (isset($posts['total']) && ($posts['total'] !== '')?$posts['total']:'0'); ?></span></h2>
                </div>
                <div id="postList">
                    <?php if(is_array($posts) || $posts instanceof \think\Collection || $posts instanceof \think\Paginator): $i = 0; $__LIST__ = $posts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$post): $mod = ($i % 2 );++$i;?>
                    <div class="square-card">
                        <div class="pet-record">
                            <?php if(isset($users[$post['user_id']]) && isset($users[$post['user_id']]['avatar']) && $users[$post['user_id']]['avatar']): ?>
                            <img src="<?php echo $users[$post['user_id']]['avatar']; ?>" alt="<?php echo $users[$post['user_id']]['username']; ?>" class="pet-avatar-small">
                            <?php else: ?>
                            <div class="pet-avatar-placeholder"><?php echo (strtoupper(substr($users[$post['user_id']]['username'],0,1)) ?: 'U'); ?></div>
                            <?php endif; ?>
                            <div class="pet-info">
                                <div class="pet-name">
                                    <span class="pet-name-icon">📮</span>
                                    <?php echo $post['title']; ?>
                                </div>
                                <div class="pet-owner"><?php echo (isset($users[$post['user_id']]['username']) && ($users[$post['user_id']]['username'] !== '')?$users[$post['user_id']]['username']:'未知用户'); ?> · <?php echo $post['created_at']; ?></div>
                                <div class="pet-content"><?php echo $post['content']; ?></div>
                                <?php if($post['image']): ?>
                                <img src="<?php echo $post['image']; ?>" class="pet-image" alt="帖子图片">
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
                                    <button class="interaction-btn" onclick="location.href='/post/detail/<?php echo $post['id']; ?>'">
                                        <span>💬</span> 查看详情
                                    </button>
                                    <?php if(session('user_id') == $post['user_id'] || (isset($can_moderate_all) && $can_moderate_all)): ?>
                                    <button class="interaction-btn delete-btn" onclick="deletePost(<?php echo $post['id']; ?>)" style="margin-left:auto;">
                                        🗑️ 删除
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                
                <?php if(isset($posts['total']) && $posts['total'] > 5): ?>
                <div class="pagination">
                    <?php if($posts['current_page'] > 1): ?>
                    <a href="?post_page=<?php echo $posts['current_page']-1; ?>&log_page=<?php echo $logs['current_page']; ?>">上一页</a>
                    <?php else: ?>
                    <span class="disabled">上一页</span>
                    <?php endif; ?>
                    <span class="active"><?php echo $posts['current_page']; ?> / <?php echo $posts['last_page']; ?></span>
                    <?php if($posts['current_page'] < $posts['last_page']): ?>
                    <a href="?post_page=<?php echo $posts['current_page']+1; ?>&log_page=<?php echo $logs['current_page']; ?>">下一页</a>
                    <?php else: ?>
                    <span class="disabled">下一页</span>
                    <?php endif; ?>
                    <span style="color:#999;font-size:13px;margin-left:8px;">共 <?php echo $posts['total']; ?> 条</span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="right-sidebar">
            <div class="section">
                <div class="section-header">
                    <h2>🐾 宠物生活 <span class="section-badge"><?php echo (isset($logs['total']) && ($logs['total'] !== '')?$logs['total']:'0'); ?></span></h2>
                </div>
                <?php if(is_array($logs) || $logs instanceof \think\Collection || $logs instanceof \think\Paginator): $i = 0; $__LIST__ = $logs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$log): $mod = ($i % 2 );++$i;?>
                <div class="square-card">
                    <div class="pet-record">
                        <?php if(isset($users[$log['user_id']]) && isset($users[$log['user_id']]['avatar']) && $users[$log['user_id']]['avatar']): ?>
                        <img src="<?php echo $users[$log['user_id']]['avatar']; ?>" alt="<?php echo $users[$log['user_id']]['username']; ?>" class="pet-avatar-small">
                        <?php else: ?>
                        <div class="pet-avatar-placeholder"><?php echo (strtoupper(substr($users[$log['user_id']]['username'],0,1)) ?: 'U'); ?></div>
                        <?php endif; ?>
                        <div class="pet-info">
                            <div class="pet-name">
                                <span class="pet-name-icon">🐾</span>
                                <?php echo $log['pet_name']; ?>
                            </div>
                            <div class="pet-owner"><?php echo (isset($users[$log['user_id']]['username']) && ($users[$log['user_id']]['username'] !== '')?$users[$log['user_id']]['username']:'未知用户'); ?> · <?php echo $log['create_time']; ?></div>
                            <div class="pet-content"><?php if(isset($log['content']) && strlen($log['content']) > 50): ?><?php echo substr($log['content'], 0, 50); ?>...<?php else: ?><?php echo $log['content']; endif; ?></div>
                            <?php if(isset($log['image']) && $log['image']): ?>
                            <img src="<?php echo $log['image']; ?>" class="pet-image" alt="宠物图片" style="max-height:180px;">
                            <?php endif; ?>
                            <div class="interaction-bar">
                                <button class="interaction-btn" onclick="toggleLike('pet_log', <?php echo $log['id']; ?>, this)" data-liked="false">
                                    <span>❤️</span>
                                    <span class="count">0</span>
                                </button>
                                <?php if(session('user_id') == $log['user_id'] || (isset($can_moderate_all) && $can_moderate_all)): ?>
                                <button class="interaction-btn delete-btn" onclick="deleteLog(<?php echo $log['id']; ?>)" style="margin-left:auto;">
                                    🗑️
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; endif; else: echo "" ;endif; if(isset($logs['total']) && $logs['total'] > 5): ?>
                <div class="pagination">
                    <?php if($logs['current_page'] > 1): ?>
                    <a href="?log_page=<?php echo $logs['current_page']-1; ?>&post_page=<?php echo $posts['current_page']; ?>">上一页</a>
                    <?php else: ?>
                    <span class="disabled">上一页</span>
                    <?php endif; ?>
                    <span class="active"><?php echo $logs['current_page']; ?> / <?php echo $logs['last_page']; ?></span>
                    <?php if($logs['current_page'] < $logs['last_page']): ?>
                    <a href="?log_page=<?php echo $logs['current_page']+1; ?>&post_page=<?php echo $posts['current_page']; ?>">下一页</a>
                    <?php else: ?>
                    <span class="disabled">下一页</span>
                    <?php endif; ?>
                    <span style="color:#999;font-size:13px;margin-left:8px;">共 <?php echo $logs['total']; ?> 条</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        var PAGE_CSRF = '<?php echo (isset($csrf_token) && ($csrf_token !== '')?$csrf_token:""); ?>';
        var CURRENT_USER_ID = '<?php echo (\think\Session::get('user_id') ?: ""); ?>';

        function toggleUserDropdown() {
            document.getElementById('userDropdown').classList.toggle('show');
        }
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.user-avatar') && !e.target.closest('.user-dropdown')) {
                document.getElementById('userDropdown').classList.remove('show');
            }
        });

        function deleteLog(logId) {
            if (!confirm('确定要删除这条宠物记录吗？')) return;
            fetch('/pet/log/' + logId, {
                method: 'DELETE',
                headers: {'X-Requested-With': 'XMLHttpRequest', 'X-Csrf-Token': PAGE_CSRF}
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.code === 200) {
                    showToast('删除成功');
                    setTimeout(function() { location.reload(); }, 800);
                } else {
                    showToast(data.msg || '删除失败', 'error');
                }
            })
            .catch(function() { showToast('网络错误', 'error'); });
        }

        function deletePost(postId) {
            if (!confirm('确定要删除这篇帖子吗？')) return;
            location.href = '/post/delete/' + postId + '?csrf=' + encodeURIComponent(PAGE_CSRF);
        }

        var giftToUserId = 0, giftTargetType = '', giftTargetId = 0, selectedGiftType = '';

        function toggleLike(targetType, targetId, btn) {
            if (!CURRENT_USER_ID) { showToast('请先登录'); return; }
            fetch('/interaction/like_toggle', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'target_type=' + targetType + '&target_id=' + targetId
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.code === 200) {
                    btn.classList.toggle('active-like', data.liked);
                    btn.dataset.liked = data.liked;
                    var countEl = btn.querySelector('.count');
                    if (countEl) countEl.textContent = data.count;
                } else {
                    showToast(data.msg || '操作失败', 'error');
                }
            })
            .catch(function() { showToast('网络错误', 'error'); });
        }

        function toggleBookmark(targetType, targetId, btn) {
            if (!CURRENT_USER_ID) { showToast('请先登录'); return; }
            fetch('/interaction/bookmark_toggle', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'target_type=' + targetType + '&target_id=' + targetId + '&folder=默认收藏'
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.code === 200) {
                    btn.classList.toggle('active-bookmark', data.bookmarked);
                    btn.dataset.bookmarked = data.bookmarked;
                    showToast(data.msg);
                } else {
                    showToast(data.msg || '操作失败', 'error');
                }
            })
            .catch(function() { showToast('网络错误', 'error'); });
        }

        function openGiftModal(toUserId, targetType, targetId) {
            if (!CURRENT_USER_ID) { showToast('请先登录'); return; }
            giftToUserId = toUserId;
            giftTargetType = targetType;
            giftTargetId = targetId;
            selectedGiftType = '';
            document.getElementById('sendGiftBtn').disabled = true;
            document.getElementById('giftMessage').value = '';
            fetch('/interaction/gift_types')
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.code === 200) {
                        var grid = document.getElementById('giftGrid');
                        grid.innerHTML = '';
                        data.data.forEach(function(gift) {
                            var div = document.createElement('div');
                            div.className = 'gift-option';
                            div.onclick = function() {
                                selectedGiftType = gift.type;
                                document.querySelectorAll('.gift-option').forEach(function(el) { el.classList.remove('selected'); });
                                div.classList.add('selected');
                                document.getElementById('sendGiftBtn').disabled = false;
                                document.getElementById('sendGiftBtn').style.background = 'linear-gradient(135deg, #ff9900, #ff6600)';
                                document.getElementById('sendGiftBtn').style.cursor = 'pointer';
                            };
                            div.innerHTML = '<div class="icon">' + gift.icon + '</div><div class="name">' + gift.name + '</div><div class="price">' + gift.price + ' 积分</div>';
                            grid.appendChild(div);
                        });
                        document.getElementById('giftModal').classList.add('show');
                    }
                })
                .catch(function() { showToast('加载礼物列表失败', 'error'); });
        }

        function closeGiftModal() {
            document.getElementById('giftModal').classList.remove('show');
        }

        function confirmSendGift() {
            if (!selectedGiftType) { showToast('请选择礼物'); return; }
            var message = document.getElementById('giftMessage').value;
            fetch('/interaction/send_gift', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'to_user_id=' + giftToUserId + '&gift_type=' + selectedGiftType + '&message=' + encodeURIComponent(message) + '&target_type=' + giftTargetType + '&target_id=' + giftTargetId
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.code === 200) {
                    showToast('🎁 礼物已送出！');
                    closeGiftModal();
                } else {
                    showToast(data.msg || '送礼失败', 'error');
                }
            })
            .catch(function() { showToast('网络错误', 'error'); });
        }

        function showToast(message, type) {
            type = type || 'success';
            var toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = 'toast' + (type === 'error' ? ' error' : '');
            toast.classList.add('show');
            setTimeout(function() { toast.classList.remove('show'); }, 2500);
        }
    </script>
</body>
</html>