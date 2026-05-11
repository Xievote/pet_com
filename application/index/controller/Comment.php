<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use app\index\model\Comment as CommentModel;
use app\common\SuperAdmin;
use app\index\service\UserService;

class Comment extends Controller
{
    public function save(Request $request)
    {
        if (!session('user_id')) {
            $this->redirect('login');
        }

        $posted = $request->post('csrf_token', '');
        if ($posted === '' || $posted !== session('csrf_token')) {
            $this->error('请求无效或已过期，请刷新页面后重试');
        }

        $data = $request->only(['post_id', 'content', 'parent_id']);
        $validate = $this->validate($data, [
            'post_id|帖子ID' => 'require|number',
            'content|评论内容' => 'require',
        ]);

        if ($validate !== true) {
            $this->error($validate);
        }

        $data['content'] = htmlspecialchars($data['content']);
        $data['user_id'] = session('user_id');

        $file = $request->file('image');
        if ($file) {
            $info = $file->validate([
                'size' => 2097152,
                'ext' => 'jpg,png,jpeg,gif',
            ])->move('uploads/comments');

            if ($info) {
                $data['image'] = '/uploads/comments/' . $info->getSaveName();
            } else {
                $this->error($file->getError());
            }
        }

        $comment = CommentModel::create($data);
        if ($comment) {
            UserService::awardExp(session('user_id'), 'comment');
            $this->success('评论发布成功', 'post/detail?id=' . $data['post_id']);
        }
        $this->error('评论发布失败');
    }

    public function delete($id)
    {
        if (!session('user_id')) {
            $this->redirect('/login');
        }

        $t = $this->request->get('csrf', '');
        if ($t === '' || $t !== session('csrf_token')) {
            $this->error('请求无效或已过期，请刷新页面后重试');
        }

        $userId = session('user_id');
        $isSuper = SuperAdmin::verifyFromDb($userId);

        $comment = CommentModel::find($id);
        if (!$comment) {
            $this->error('评论不存在');
        }

        if (!$isSuper && (int) $comment->user_id !== (int) $userId) {
            $this->error('无权限删除此评论');
        }

        $post_id = $comment->post_id;
        $result = $comment->delete();
        if ($result) {
            $this->success('评论删除成功', 'post/detail?id=' . $post_id);
        }
        $this->error('评论删除失败');
    }
}
