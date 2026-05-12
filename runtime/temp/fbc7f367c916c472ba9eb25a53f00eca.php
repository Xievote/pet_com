<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"C:\Users\EDY\tp5\public/../application/index\view\pet\message.html";i:1778553167;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <title>消息中心</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; background: #f5f5f5; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { margin: 0; }
        .back-link { color: #ff9900; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }

        .tabs { display: flex; gap: 10px; margin-bottom: 20px; }
        .tab-btn { padding: 12px 24px; background: white; border: 2px solid #ff9900; border-radius: 25px; cursor: pointer; font-size: 15px; transition: all 0.3s; }
        .tab-btn.active { background: #ff9900; color: white; }
        .tab-btn:hover:not(.active) { background: #fff8f0; }

        .message-list { background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        .message-item { display: flex; align-items: center; padding: 15px 20px; border-bottom: 1px solid #eee; cursor: pointer; transition: background 0.3s; }
        .message-item:hover { background: #f9f9f9; }
        .message-item:last-child { border-bottom: none; }
        .message-item.unread { background: #fff9f0; }
        .message-item.unread:hover { background: #fff5e0; }

        .message-avatar { width: 50px; height: 50px; border-radius: 50%; background: #ff9900; color: white; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: bold; margin-right: 15px; flex-shrink: 0; }
        .message-avatar img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }

        .message-content { flex: 1; min-width: 0; }
        .message-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
        .message-name { font-weight: bold; color: #333; font-size: 15px; }
        .message-time { color: #999; font-size: 12px; }
        .message-preview { color: #666; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .message-badge { background: #e74c3c; color: white; font-size: 12px; padding: 2px 8px; border-radius: 10px; margin-left: 8px; }

        .notification-icon { width: 40px; height: 40px; border-radius: 50%; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-right: 12px; flex-shrink: 0; }
        .notification-content-text { font-size: 14px; color: #333; }
        .notification-target { color: #ff9900; font-weight: bold; }

        .empty-state { text-align: center; padding: 60px 20px; color: #999; }
        .empty-state .icon { font-size: 60px; margin-bottom: 15px; }

        .toast { position: fixed; top: 20px; left: 50%; transform: translateX(-50%) translateY(-100px); background: #27ae60; color: white; padding: 15px 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 9999; opacity: 0; transition: all 0.3s ease; }
        .toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }
        .toast.error { background: #e74c3c; }

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        .notification-time { font-size: 12px; color: #999; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="toast" id="toast"></div>

    <div class="header">
        <h1>💬 消息中心</h1>
        <a href="/index" class="back-link">← 返回首页</a>
    </div>

    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('notifications')">
            🔔 通知 <span id="notificationCount"></span>
        </button>
        <button class="tab-btn" onclick="switchTab('messages')">
            💬 私信 <span id="messageCount"></span>
        </button>
    </div>

    <div id="notificationsTab" class="tab-content active">
        <div class="message-list" id="notificationsList">
            <div class="empty-state">
                <div class="icon">🔔</div>
                <p>暂无通知</p>
            </div>
        </div>
        <div style="text-align:center;padding:15px;">
            <button onclick="markAllNotificationsRead()" style="background:#f0f0f0;border:none;padding:10px 20px;border-radius:20px;cursor:pointer;">全部标为已读</button>
        </div>
    </div>

    <div id="messagesTab" class="tab-content">
        <div class="message-list" id="messagesList">
            <div class="empty-state">
                <div class="icon">💬</div>
                <p>暂无私信</p>
            </div>
        </div>
    </div>

    <script>
        var currentUserId = '<?php echo (\think\Session::get('user_id') ?: ""); ?>';
        var currentUsername = '<?php echo (\think\Session::get('username') ?: ""); ?>';

        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
            loadMessages();
            loadUnreadCounts();
        });

        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(function(btn) { btn.classList.remove('active'); });
            document.querySelectorAll('.tab-content').forEach(function(content) { content.classList.remove('active'); });

            if (tab === 'notifications') {
                document.querySelector('.tab-btn:nth-child(1)').classList.add('active');
                document.getElementById('notificationsTab').classList.add('active');
            } else {
                document.querySelector('.tab-btn:nth-child(2)').classList.add('active');
                document.getElementById('messagesTab').classList.add('active');
            }
        }

        function loadUnreadCounts() {
            fetch('/message/unread_count?t=' + Date.now())
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.code === 200) {
                        var notifCount = document.getElementById('notificationCount');
                        var msgCount = document.getElementById('messageCount');
                        notifCount.textContent = data.data.notification_count > 0 ? ' (' + data.data.notification_count + ')' : '';
                        msgCount.textContent = data.data.message_count > 0 ? ' (' + data.data.message_count + ')' : '';
                    }
                })
                .catch(function(e) { console.error(e); });
        }

        function loadNotifications() {
            fetch('/message/notifications?t=' + Date.now())
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.code === 200) {
                        renderNotifications(data.data);
                    }
                })
                .catch(function(e) { console.error(e); });
        }

        function renderNotifications(notifications) {
            var container = document.getElementById('notificationsList');
            if (!notifications || notifications.length === 0) {
                container.innerHTML = '<div class="empty-state"><div class="icon">🔔</div><p>暂无通知</p></div>';
                return;
            }

            var html = '';
            notifications.forEach(function(n) {
                var iconMap = {
                    'like': '❤️',
                    'comment': '💬',
                    'bookmark': '⭐',
                    'gift': '🎁',
                    'message': '💬',
                    'system': '🔔'
                };
                var icon = iconMap[n.type] || '🔔';
                var timeAgo = getTimeAgo(n.created_at);
                var unreadClass = n.is_read == 0 ? 'unread' : '';

                html += '<div class="message-item ' + unreadClass + '" onclick="markNotificationRead(' + n.id + ')">';
                html += '<div class="notification-icon">' + icon + '</div>';
                html += '<div class="message-content">';
                html += '<div class="message-header">';
                html += '<span class="message-name">' + n.content + '</span>';
                html += '<span class="message-time">' + timeAgo + '</span>';
                html += '</div>';
                if (n.target_type) {
                    html += '<div class="notification-content-text">查看详情 →</div>';
                }
                html += '</div>';
                html += '</div>';
            });

            container.innerHTML = html;
        }

        function loadMessages() {
            fetch('/message/list?t=' + Date.now())
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.code === 200) {
                        renderMessages(data.data);
                    }
                })
                .catch(function(e) { console.error(e); });
        }

        function renderMessages(messages) {
            var container = document.getElementById('messagesList');
            if (!messages || messages.length === 0) {
                container.innerHTML = '<div class="empty-state"><div class="icon">💬</div><p>暂无私信</p></div>';
                return;
            }

            var html = '';
            messages.forEach(function(m) {
                var timeAgo = getTimeAgo(m.created_at);
                var unreadClass = m.is_read == 0 ? 'unread' : '';
                var initial = m.from_user_id == currentUserId ? '发' : (m.from_username ? m.from_username.charAt(0).toUpperCase() : 'U');

                html += '<div class="message-item ' + unreadClass + '">';
                html += '<div class="message-avatar">' + initial + '</div>';
                html += '<div class="message-content">';
                html += '<div class="message-header">';
                html += '<span class="message-name">' + (m.from_user_id == currentUserId ? '发给: ' + (m.to_username || '用户') : (m.from_username || '用户')) + '</span>';
                html += '<span class="message-time">' + timeAgo + '</span>';
                html += '</div>';
                html += '<div class="message-preview">' + m.content + '</div>';
                html += '</div>';
                html += '</div>';
            });

            container.innerHTML = html;
        }

        function markNotificationRead(id) {
            fetch('/message/mark_read?notification_id=' + id, {method: 'POST'})
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.code === 200) {
                        loadNotifications();
                        loadUnreadCounts();
                    }
                })
                .catch(function(e) { console.error(e); });
        }

        function markAllNotificationsRead() {
            fetch('/message/mark_all_read', {method: 'POST'})
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.code === 200) {
                        showToast('所有通知已标记为已读');
                        loadNotifications();
                        loadUnreadCounts();
                    }
                })
                .catch(function(e) { console.error(e); });
        }

        function getTimeAgo(datetime) {
            if (!datetime) return '';
            var date = new Date(datetime);
            var now = new Date();
            var diff = Math.floor((now - date) / 1000);

            if (diff < 60) return '刚刚';
            if (diff < 3600) return Math.floor(diff / 60) + '分钟前';
            if (diff < 86400) return Math.floor(diff / 3600) + '小时前';
            if (diff < 604800) return Math.floor(diff / 86400) + '天前';
            return datetime.substring(0, 10);
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