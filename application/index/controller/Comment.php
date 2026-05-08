<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use app\index\model\Comment as CommentModel;

class Comment extends Controller
{
    // 添加评论
    public function save(Request $request)
    {
        // 检查登录状态
        if (!session('user_id')) {
            $this->redirect('UserLogin/login');
        }
        
        // 验证数据
        $data = $request->only(['post_id', 'content', 'parent_id']);
        $validate = $this->validate($data, [
            'post_id|帖子ID' => 'require|number',
            'content|评论内容' => 'require',
        ]);
        
        if ($validate !== true) {
            $this->error($validate);
        }
        
        // 防XSS攻击
        $data['content'] = htmlspecialchars($data['content']);
        
        // 关联用户ID
        $data['user_id'] = session('user_id');
        
        // 保存评论
        $comment = CommentModel::create($data);
        if ($comment) {
            $this->success('评论发布成功', 'post/detail?id=' . $data['post_id']);
        } else {
            $this->error('评论发布失败');
        }
    }
    
    // 删除评论
    public function delete($id)
    {
        // 检查登录状态
        if (!session('user_id')) {
            $this->redirect('UserLogin/login');
        }
        
        $comment = CommentModel::find($id);
        if (!$comment) {
            $this->error('评论不存在');
        }
        
        // 检查权限
        if ($comment->user_id != session('user_id')) {
            $this->error('无权限删除此评论');
        }
        
        // 保存帖子ID用于跳转
        $post_id = $comment->post_id;
        
        // 删除评论（子评论会通过外键级联删除）
        $result = $comment->delete();
        if ($result) {
            $this->success('评论删除成功', 'post/detail?id=' . $post_id);
        } else {
            $this->error('评论删除失败');
        }
    }
}
