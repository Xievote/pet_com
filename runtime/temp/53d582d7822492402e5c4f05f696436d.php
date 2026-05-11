<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:70:"C:\Users\EDY\tp5\public/../application/index\view\pet\achievement.html";i:1778488789;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <title>等级与成就</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; background: #f5f5f5; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { margin: 0; }
        .back-link { color: #ff9900; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }

        .card { background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 25px; margin-bottom: 25px; }

        .level-card { text-align: center; }
        .level-badge { width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #ff9900, #ff6600); color: white; display: inline-flex; align-items: center; justify-content: center; font-size: 40px; font-weight: bold; margin-bottom: 15px; box-shadow: 0 4px 15px rgba(255,153,0,0.4); }
        .level-name { font-size: 22px; font-weight: bold; color: #333; margin-bottom: 5px; }
        .level-subtitle { color: #999; font-size: 14px; margin-bottom: 15px; }

        .exp-bar-container { background: #eee; border-radius: 10px; height: 20px; overflow: hidden; margin: 15px 0; }
        .exp-bar-fill { height: 100%; background: linear-gradient(90deg, #ff9900, #ffcc00); border-radius: 10px; transition: width 0.5s ease; }
        .exp-text { display: flex; justify-content: space-between; font-size: 13px; color: #666; margin-top: 5px; }

        .checkin-section { text-align: center; margin-bottom: 20px; }
        .checkin-btn { padding: 14px 40px; background: linear-gradient(135deg, #ff9900, #ff6600); color: white; border: none; border-radius: 25px; font-size: 18px; font-weight: bold; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 12px rgba(255,153,0,0.3); }
        .checkin-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,153,0,0.4); }
        .checkin-btn:disabled { background: #ccc; box-shadow: none; cursor: not-allowed; transform: none; }

        .consecutive-days { display: inline-flex; align-items: center; gap: 8px; background: #fff8f0; padding: 8px 16px; border-radius: 20px; margin-top: 10px; font-size: 14px; color: #ff9900; font-weight: bold; }

        .section-title { font-size: 20px; font-weight: bold; margin-bottom: 20px; color: #333; border-left: 4px solid #ff9900; padding-left: 10px; }

        .achievement-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 15px; }
        .achievement-item { background: #fafafa; border-radius: 12px; padding: 20px 15px; text-align: center; transition: all 0.3s; border: 2px solid transparent; }
        .achievement-item.unlocked { background: #fffdf5; border-color: #ffcc00; }
        .achievement-item.locked { opacity: 0.5; filter: grayscale(100%); }
        .achievement-icon { font-size: 36px; margin-bottom: 8px; }
        .achievement-name { font-size: 14px; font-weight: bold; color: #333; margin-bottom: 4px; }
        .achievement-desc { font-size: 11px; color: #999; }

        .exp-rules-table { width: 100%; border-collapse: collapse; }
        .exp-rules-table th, .exp-rules-table td { padding: 10px 14px; text-align: left; border-bottom: 1px solid #eee; }
        .exp-rules-table th { background: #fafafa; font-weight: bold; color: #333; font-size: 13px; }
        .exp-rules-table td { font-size: 14px; color: #666; }
        .exp-value { color: #ff9900; font-weight: bold; }

        .toast { position: fixed; top: 20px; left: 50%; transform: translateX(-50%) translateY(-100px); background: #27ae60; color: white; padding: 15px 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 9999; opacity: 0; transition: all 0.3s ease; }
        .toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }
        .toast.error { background: #e74c3c; }
    </style>
</head>
<body>
    <div class="toast" id="toast"></div>

    <div class="header">
        <h1>🏆 我的等级与成就</h1>
        <a href="/index" class="back-link">← 返回首页</a>
    </div>

    <!-- 签到区域 -->
    <div class="checkin-section">
        <button class="checkin-btn" id="checkinBtn" onclick="doCheckin()">
            🐾 签到打卡
        </button>
        <?php if(isset($level_info) && $level_info['checked_in_today']): ?>
        <div class="consecutive-days">
            🔥 连续签到 <?php echo $level_info['checkin_days']; ?> 天
        </div>
        <?php endif; ?>
    </div>

    <!-- 等级卡片 -->
    <div class="card level-card">
        <div class="level-badge" id="levelBadge">
            <?php if(isset($level_info)): ?>Lv.<?php echo $level_info['level']; else: ?>Lv.0<?php endif; ?>
        </div>
        <?php $level = isset($level_info) ? (int)$level_info['level'] : 0; ?>
        <div class="level-name">
            <?php if($level >= 10): ?>🏅 铲屎官之王
            <?php elseif($level >= 7): ?>🌟 资深铲屎官
            <?php elseif($level >= 5): ?>🐾 铲屎达人
            <?php elseif($level >= 3): ?>😺 爱宠新手
            <?php elseif($level >= 1): ?>🌱 萌新上路
            <?php else: ?>🆕 初心者<?php endif; ?>
        </div>
        <?php if(isset($level_info)): ?>
        <div class="level-subtitle">
            🔥 <?php echo $level_info['exp']; ?> / <?php echo $level_info['next_level_exp']; ?> EXP
        </div>
        <div class="exp-bar-container">
            <div class="exp-bar-fill" style="width: <?php echo $level_info['progress']; ?>%;"></div>
        </div>
        <div class="exp-text">
            <span>当前等级 Lv.<?php echo $level_info['level']; ?></span>
            <span>下一级 Lv.<?php echo $level_info['level']+1; ?></span>
        </div>
        <?php endif; ?>
    </div>

    <!-- 经验值获取规则 -->
    <div class="card">
        <div class="section-title">经验值获取规则</div>
        <table class="exp-rules-table">
            <tr><th>行为</th><th>奖励经验</th><th>每日上限</th></tr>
            <tr><td>每日签到</td><td class="exp-value">+<?php echo (\think\Request::instance()->get('exp_checkin') ?: '10'); ?> EXP</td><td>1次/日</td></tr>
            <tr><td>连续签到奖励</td><td class="exp-value">+5×天数 EXP</td><td>-</td></tr>
            <tr><td>发布帖子</td><td class="exp-value">+30 EXP</td><td>3次/日</td></tr>
            <tr><td>发布宠物记录</td><td class="exp-value">+20 EXP</td><td>3次/日</td></tr>
            <tr><td>评论</td><td class="exp-value">+5 EXP</td><td>10次/日</td></tr>
            <tr><td>获得点赞</td><td class="exp-value">+2 EXP</td><td>20次/日</td></tr>
        </table>
    </div>

    <!-- 成就徽章墙 -->
    <div class="card">
        <div class="section-title">成就徽章</div>
        <div class="achievement-grid">
            <?php if(isset($achievements)): if(is_array($achievements) || $achievements instanceof \think\Collection || $achievements instanceof \think\Paginator): $i = 0; $__LIST__ = $achievements;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ach): $mod = ($i % 2 );++$i;?>
            <div class="achievement-item <?php echo !empty($ach['unlocked'])?'unlocked' : 'locked'; ?>">
                <div class="achievement-icon"><?php echo $ach['icon']; ?></div>
                <div class="achievement-name"><?php echo $ach['name']; ?></div>
                <div class="achievement-desc"><?php echo $ach['description']; ?></div>
                <?php if(!$ach['unlocked']): ?>
                <div class="achievement-desc"><?php echo $ach['progress']['current']; ?>/<?php echo $ach['progress']['target']; ?></div>
                <?php endif; ?>
            </div>
            <?php endforeach; endif; else: echo "" ;endif; endif; ?>
        </div>
        <?php $totalUnlocked = isset($achievements) ? count(array_filter($achievements, function($a){return $a['unlocked'];})) : 0; $totalAchievements = isset($achievements) ? count($achievements) : 0; ?>
        <div style="text-align:center;margin-top:15px;color:#999;font-size:14px;">
            <?php echo $totalUnlocked . '/' . $totalAchievements; ?> 个成就已解锁
        </div>
    </div>

    <script>
        function doCheckin() {
            var btn = document.getElementById('checkinBtn');
            btn.disabled = true;
            btn.textContent = '签到中...';

            fetch('/achievement/checkin', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.code === 200) {
                    if (data.duplicate) {
                        showToast(data.msg);
                        btn.disabled = false;
                        btn.textContent = '已签到 ✓';
                    } else {
                        showToast(data.msg);
                        btn.textContent = '已签到 ✓';
                        var d = data.data;
                        // 更新等级显示
                        document.getElementById('levelBadge').textContent = 'Lv.' + d.level;
                        // 刷新页面以更新全部数据
                        setTimeout(function() { location.reload(); }, 1500);
                    }
                } else {
                    showToast(data.msg || '签到失败', 'error');
                    btn.disabled = false;
                    btn.textContent = '🐾 签到打卡';
                }
            })
            .catch(function(e) {
                console.error(e);
                showToast('网络错误', 'error');
                btn.disabled = false;
                btn.textContent = '🐾 签到打卡';
            });
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