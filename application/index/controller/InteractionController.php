<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\Like;
use app\index\model\Bookmark;
use app\index\model\Gift;
use app\index\model\User;
use app\index\model\PetLog;
use app\index\model\Post;
use app\index\service\UserService;

class InteractionController extends Controller
{
    /**
     * 点赞/取消点赞
     */
    public function likeToggle(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $targetType = $request->post('target_type');
        $targetId = $request->post('target_id');

        if (!in_array($targetType, ['post', 'comment', 'pet_log'], true)) {
            return json(['code' => 400, 'msg' => '目标类型无效']);
        }

        if (!$targetId) {
            return json(['code' => 400, 'msg' => '目标ID不能为空']);
        }

        try {
            $like = Like::where([
                'user_id' => $userId,
                'target_type' => $targetType,
                'target_id' => $targetId
            ])->find();

            if ($like) {
                $like->delete();
                $count = Like::where(['target_type' => $targetType, 'target_id' => $targetId])->count();
                return json(['code' => 200, 'msg' => '取消点赞', 'liked' => false, 'count' => $count]);
            } else {
                $newLike = new Like();
                $newLike->user_id = $userId;
                $newLike->target_type = $targetType;
                $newLike->target_id = $targetId;
                $newLike->save();

                $ownerId = 0;
                if ($targetType === 'post') {
                    $target = Post::field('user_id')->find($targetId);
                    $ownerId = $target ? $target->user_id : 0;
                } elseif ($targetType === 'pet_log') {
                    $target = PetLog::field('user_id')->find($targetId);
                    $ownerId = $target ? $target->user_id : 0;
                }
                if ($ownerId && (int)$ownerId !== (int)$userId) {
                    UserService::awardExp($ownerId, 'receive_like');
                }

                $count = Like::where(['target_type' => $targetType, 'target_id' => $targetId])->count();
                return json(['code' => 200, 'msg' => '点赞成功', 'liked' => true, 'count' => $count]);
            }
        } catch (\Exception $e) {
            return json(['code' => 500, 'msg' => '操作失败：' . $e->getMessage()]);
        }
    }

    /**
     * 收藏/取消收藏
     */
    public function bookmarkToggle(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $targetType = $request->post('target_type');
        $targetId = $request->post('target_id');
        $folder = $request->post('folder', '默认收藏');

        if (!in_array($targetType, ['post', 'pet_log'], true)) {
            return json(['code' => 400, 'msg' => '目标类型无效']);
        }

        if (!$targetId) {
            return json(['code' => 400, 'msg' => '目标ID不能为空']);
        }

        try {
            $bookmark = Bookmark::where([
                'user_id' => $userId,
                'target_type' => $targetType,
                'target_id' => $targetId
            ])->find();

            if ($bookmark) {
                $bookmark->delete();
                return json(['code' => 200, 'msg' => '已取消收藏', 'bookmarked' => false]);
            } else {
                $newBookmark = new Bookmark();
                $newBookmark->user_id = $userId;
                $newBookmark->target_type = $targetType;
                $newBookmark->target_id = $targetId;
                $newBookmark->folder = $folder;
                $newBookmark->save();

                if ($targetType === 'post') {
                    $post = Post::field('user_id')->find($targetId);
                    if ($post && (int)$post->user_id !== (int)$userId) {
                        UserService::checkAchievements($post->user_id);
                    }
                }

                return json(['code' => 200, 'msg' => '已收藏到「' . $folder . '」', 'bookmarked' => true]);
            }
        } catch (\Exception $e) {
            return json(['code' => 500, 'msg' => '操作失败：' . $e->getMessage()]);
        }
    }

    /**
     * 获取收藏列表
     */
    public function bookmarkList(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $targetType = $request->param('target_type', '');

        try {
            $query = Bookmark::where('user_id', $userId)->order('created_at', 'desc');

            if ($targetType && in_array($targetType, ['post', 'pet_log'], true)) {
                $query->where('target_type', $targetType);
            }

            $bookmarks = $query->select();

            return json(['code' => 200, 'data' => $bookmarks]);
        } catch (\Exception $e) {
            return json(['code' => 500, 'msg' => '获取收藏列表失败：' . $e->getMessage()]);
        }
    }

    /**
     * 发送礼物
     */
    public function sendGift(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $toUserId = $request->post('to_user_id');
        $giftType = $request->post('gift_type');
        $message = $request->post('message', '');
        $targetType = $request->post('target_type', '');
        $targetId = $request->post('target_id', 0);

        if (!$toUserId) {
            return json(['code' => 400, 'msg' => '收礼用户ID不能为空']);
        }

        $giftPrices = [
            'bone' => 5,
            'fish' => 10,
            'heart' => 20,
            'rose' => 50
        ];

        if (!isset($giftPrices[$giftType])) {
            return json(['code' => 400, 'msg' => '礼物类型无效']);
        }

        $price = $giftPrices[$giftType];

        $toUser = User::find($toUserId);
        if (!$toUser) {
            return json(['code' => 404, 'msg' => '收礼用户不存在']);
        }

        try {
            $gift = new Gift();
            $gift->from_user_id = $userId;
            $gift->to_user_id = $toUserId;
            $gift->gift_type = $giftType;
            $gift->price = $price;
            $gift->target_type = $targetType ?: null;
            $gift->target_id = $targetId ?: null;
            $gift->message = $message;
            $gift->save();

            return json(['code' => 200, 'msg' => '礼物已送出！', 'data' => $gift]);
        } catch (\Exception $e) {
            return json(['code' => 500, 'msg' => '送礼失败：' . $e->getMessage()]);
        }
    }

    /**
     * 获取礼物列表
     */
    public function giftList(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }

        $direction = $request->param('direction', 'received');

        try {
            if ($direction === 'sent') {
                $gifts = Gift::where('from_user_id', $userId)
                    ->order('created_at', 'desc')
                    ->select();
            } else {
                $gifts = Gift::where('to_user_id', $userId)
                    ->order('created_at', 'desc')
                    ->select();
            }

            return json(['code' => 200, 'data' => $gifts]);
        } catch (\Exception $e) {
            return json(['code' => 500, 'msg' => '获取礼物列表失败：' . $e->getMessage()]);
        }
    }

    /**
     * 获取可用礼物类型
     */
    public function giftTypes()
    {
        $types = [
            ['type' => 'bone', 'name' => '小骨头', 'price' => 5, 'icon' => '🦴'],
            ['type' => 'fish', 'name' => '小鱼干', 'price' => 10, 'icon' => '🐟'],
            ['type' => 'heart', 'name' => '爱心', 'price' => 20, 'icon' => '❤️'],
            ['type' => 'rose', 'name' => '玫瑰花', 'price' => 50, 'icon' => '🌹'],
        ];

        return json(['code' => 200, 'data' => $types]);
    }
}