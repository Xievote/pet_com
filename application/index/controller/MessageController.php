<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Message;
use app\index\model\Notification;
use app\index\model\User;

class MessageController extends Controller
{
    /**
     * 消息中心首页
     */
    public function index()
    {
        $userId = session('user_id');
        if (!$userId) {
            return $this->error('请先登录', '/login');
        }

        $this->assign('user_id', $userId);
        return $this->fetch('pet/message');
    }

    /**
     * 获取通知列表
     */
    public function getNotifications()
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $notifications = Notification::where('user_id', $userId)
            ->order('created_at', 'desc')
            ->limit(50)
            ->select();

        return json(['code' => 200, 'data' => $notifications]);
    }

    /**
     * 获取未读通知数量
     */
    public function getUnreadCount()
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $count = Notification::where('user_id', $userId)
            ->where('is_read', 0)
            ->count();

        return json(['code' => 200, 'data' => ['count' => $count]]);
    }

    /**
     * 标记通知已读
     */
    public function markRead()
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $notificationId = input('notification_id/d', 0);

        if ($notificationId > 0) {
            Notification::where('id', $notificationId)
                ->where('user_id', $userId)
                ->update(['is_read' => 1]);
        }

        return json(['code' => 200, 'msg' => '已标记为已读']);
    }

    /**
     * 标记所有通知已读
     */
    public function markAllRead()
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        Notification::where('user_id', $userId)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return json(['code' => 200, 'msg' => '所有通知已标记为已读']);
    }

    /**
     * 获取私信列表
     */
    public function getMessages()
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $messages = Message::where('to_user_id', $userId)
            ->order('created_at', 'desc')
            ->limit(50)
            ->select();

        return json(['code' => 200, 'data' => $messages]);
    }

    /**
     * 获取与某个用户的对话
     */
    public function getConversation()
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $targetUserId = input('target_user_id/d', 0);
        if (!$targetUserId) {
            return json(['code' => 400, 'msg' => '用户ID不能为空']);
        }

        $messages = Message::where(function($query) use ($userId, $targetUserId) {
                $query->where('from_user_id', $userId)
                      ->where('to_user_id', $targetUserId);
            })
            ->whereOr(function($query) use ($userId, $targetUserId) {
                $query->where('from_user_id', $targetUserId)
                      ->where('to_user_id', $userId);
            })
            ->order('created_at', 'asc')
            ->select();

        return json(['code' => 200, 'data' => $messages]);
    }

    /**
     * 发送私信
     */
    public function sendMessage(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $toUserId = $request->post('to_user_id/d', 0);
        $content = trim($request->post('content', ''));

        if (!$toUserId) {
            return json(['code' => 400, 'msg' => '收信用户不能为空']);
        }

        if (empty($content)) {
            return json(['code' => 400, 'msg' => '消息内容不能为空']);
        }

        if (strlen($content) > 1000) {
            return json(['code' => 400, 'msg' => '消息内容不能超过1000字符']);
        }

        $toUser = User::find($toUserId);
        if (!$toUser) {
            return json(['code' => 404, 'msg' => '收信用户不存在']);
        }

        try {
            $message = new Message();
            $message->from_user_id = $userId;
            $message->to_user_id = $toUserId;
            $message->content = htmlspecialchars($content);
            $message->msg_type = 'text';
            $message->is_read = 0;
            $message->save();

            self::addNotification($toUserId, 'message', $userId, null, null, '收到一条新私信');

            return json(['code' => 200, 'msg' => '发送成功', 'data' => $message]);
        } catch (\Exception $e) {
            return json(['code' => 500, 'msg' => '发送失败：' . $e->getMessage()]);
        }
    }

    /**
     * 获取未读私信数量
     */
    public function getUnreadMessageCount()
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $count = Message::where('to_user_id', $userId)
            ->where('is_read', 0)
            ->count();

        return json(['code' => 200, 'data' => ['count' => $count]]);
    }

    /**
     * 标记私信已读
     */
    public function markMessageRead()
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $messageId = input('message_id/d', 0);

        if ($messageId > 0) {
            Message::where('id', $messageId)
                ->where('to_user_id', $userId)
                ->update(['is_read' => 1]);
        }

        return json(['code' => 200, 'msg' => '已标记为已读']);
    }

    /**
     * 添加通知
     */
    public static function addNotification($userId, $type, $fromUserId, $targetType, $targetId, $content)
    {
        $notification = new Notification();
        $notification->user_id = $userId;
        $notification->type = $type;
        $notification->from_user_id = $fromUserId;
        $notification->target_type = $targetType;
        $notification->target_id = $targetId;
        $notification->content = $content;
        $notification->is_read = 0;
        $notification->save();

        return $notification;
    }

    /**
     * 获取通知类型名称
     */
    public static function getTypeName($type)
    {
        $names = [
            'like' => '赞了',
            'comment' => '评论了',
            'bookmark' => '收藏了',
            'gift' => '送了礼物给',
            'message' => '发来私信',
            'system' => '系统通知',
            'follow' => '关注了',
        ];

        return $names[$type] ?? $type;
    }
}
