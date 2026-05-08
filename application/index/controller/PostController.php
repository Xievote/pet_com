<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Session;
use app\index\model\Post;
use app\index\model\Comment;

class PostController extends Controller
{
    // 帖子列表
    public function index()
    {
        try {
            // 启动 session
            Session::start();
            
            $posts = Post::with('user')->order('created_at desc')->paginate(10);
            $this->assign('posts', $posts);
            return $this->fetch('post/index');
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage() . '\n' . $e->getTraceAsString();
        }
    }
    
    // 帖子详情
    public function detail($id)
    {
        try {
            $post = Post::with('user')->find($id);
            if (!$post) {
                $this->error('帖子不存在');
            }
            
            // 获取评论，支持多层级
            $comments = Comment::with('user')->where('post_id', $id)->where('parent_id', 0)->order('created_at desc')->select();
            foreach ($comments as $comment) {
                $comment->children = Comment::with('user')->where('parent_id', $comment->id)->order('created_at asc')->select();
            }
            
            $commentCount = Comment::where('post_id', $id)->count();
            
            $this->assign('post', $post);
            $this->assign('comments', $comments);
            $this->assign('commentCount', $commentCount);
            return $this->fetch('post/detail');
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
    
    // 创建帖子
    public function create()
    {
        try {
            // 检查登录状态
            if (!session('user_id')) {
                $this->redirect('login');
            }
            return $this->fetch('post/create');
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
    
    // 保存帖子
    public function save(Request $request)
    {
        // 检查登录状态
        if (!session('user_id')) {
            $this->redirect('login');
        }
        
        // 验证数据
        $data = $request->only(['title', 'content']);
        $validate = $this->validate($data, [
            'title|标题' => 'require|max:255',
            'content|内容' => 'require',
        ]);
        
        if ($validate !== true) {
            $this->error($validate);
        }
        
        // 防XSS攻击
        $data['title'] = htmlspecialchars($data['title']);
        $data['content'] = htmlspecialchars($data['content']);
        
        // 关联用户ID
        $data['user_id'] = session('user_id');
        
        // 处理图片上传
        $file = $request->file('image');
        if ($file) {
            // 验证图片格式和大小
            $info = $file->validate([
                'size' => 2097152, // 2MB
                'ext' => 'jpg,png,jpeg,gif'
            ])->move('uploads/posts');
            
            if ($info) {
                $data['image'] = '/uploads/posts/' . $info->getSaveName();
            } else {
                $this->error($file->getError());
            }
        }
        
        // 保存帖子
        $post = Post::create($data);
        if ($post) {
            $this->success('帖子发布成功', 'post/index');
        } else {
            $this->error('帖子发布失败');
        }
    }
    
    // 编辑帖子
    public function edit($id)
    {
        try {
            // 检查登录状态
            if (!session('user_id')) {
                $this->redirect('login');
            }
            
            $post = Post::find($id);
            if (!$post) {
                $this->error('帖子不存在');
            }
            
            // 检查权限
            if ($post->user_id != session('user_id')) {
                $this->error('无权限编辑此帖子');
            }
            
            $this->assign('post', $post);
            return $this->fetch('post/edit');
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
    
    // 更新帖子
    public function update(Request $request, $id)
    {
        // 检查登录状态
        if (!session('user_id')) {
            $this->redirect('login');
        }
        
        $post = Post::find($id);
        if (!$post) {
            $this->error('帖子不存在');
        }
        
        // 检查权限
        if ($post->user_id != session('user_id')) {
            $this->error('无权限编辑此帖子');
        }
        
        // 验证数据
        $data = $request->only(['title', 'content']);
        $validate = $this->validate($data, [
            'title|标题' => 'require|max:255',
            'content|内容' => 'require',
        ]);
        
        if ($validate !== true) {
            $this->error($validate);
        }
        
        // 防XSS攻击
        $data['title'] = htmlspecialchars($data['title']);
        $data['content'] = htmlspecialchars($data['content']);
        
        // 更新帖子
        $result = $post->save($data);
        if ($result) {
            $this->success('帖子更新成功', 'post/detail?id=' . $id);
        } else {
            $this->error('帖子更新失败');
        }
    }
    
    // 删除帖子
    public function delete($id)
    {
        // 检查登录状态
        if (!session('user_id')) {
            $this->redirect('login');
        }
        
        $post = Post::find($id);
        if (!$post) {
            $this->error('帖子不存在');
        }
        
        // 检查权限
        if ($post->user_id != session('user_id')) {
            $this->error('无权限删除此帖子');
        }
        
        // 删除帖子（评论会通过外键级联删除）
        $result = $post->delete();
        if ($result) {
            $this->success('帖子删除成功', 'post/index');
        } else {
            $this->error('帖子删除失败');
        }
    }
}
