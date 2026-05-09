<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Session;
use app\index\model\Post;
use app\index\model\Comment;
use app\common\SuperAdmin;

class PostController extends Controller
{
    public function index()
    {
        try {
            Session::start();

            $posts = Post::with('user')->order('created_at desc')->paginate(10);
            $this->assign('posts', $posts);
            return $this->fetch('post/index');
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage() . '\n' . $e->getTraceAsString();
        }
    }

    public function detail($id)
    {
        try {
            $post = Post::with('user')->find($id);
            if (!$post) {
                $this->error('帖子不存在');
            }

            $comments = Comment::with('user')->where('post_id', $id)->where('parent_id', 0)->order('created_at desc')->select();
            foreach ($comments as $comment) {
                $comment->children = Comment::with('user')->where('parent_id', $comment->id)->order('created_at asc')->select();
            }

            $commentCount = Comment::where('post_id', $id)->count();

            $uid = session('user_id');
            $isSuper = SuperAdmin::verifyFromDb($uid);
            $csrf = bin2hex(random_bytes(16));
            session('csrf_token', $csrf);
            $backHome = $isSuper ? '/admin' : '/index';

            $this->assign('post', $post);
            $this->assign('comments', $comments);
            $this->assign('commentCount', $commentCount);
            $this->assign('csrf_token', $csrf);
            $this->assign('back_home', $backHome);
            $this->assign('is_super_moderator', $isSuper);
            return $this->fetch('post/detail');
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function create()
    {
        try {
            if (!session('user_id')) {
                $this->redirect('login');
            }
            $csrf = bin2hex(random_bytes(16));
            session('csrf_token', $csrf);
            $this->assign('csrf_token', $csrf);
            return $this->fetch('post/create');
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function save(Request $request)
    {
        if (!session('user_id')) {
            $this->redirect('login');
        }
        $posted = $request->post('csrf_token', '');
        if ($posted === '' || $posted !== session('csrf_token')) {
            $this->error('请求无效或已过期，请刷新页面后重试');
        }

        $data = $request->only(['title', 'content']);
        $validate = $this->validate($data, [
            'title|标题' => 'require|max:255',
            'content|内容' => 'require',
        ]);

        if ($validate !== true) {
            $this->error($validate);
        }

        $data['title'] = htmlspecialchars($data['title']);
        $data['content'] = htmlspecialchars($data['content']);
        $data['user_id'] = session('user_id');

        $file = $request->file('image');
        if ($file) {
            $info = $file->validate([
                'size' => 2097152,
                'ext' => 'jpg,png,jpeg,gif',
            ])->move('uploads/posts');

            if ($info) {
                $data['image'] = '/uploads/posts/' . $info->getSaveName();
            } else {
                $this->error($file->getError());
            }
        }

        $post = Post::create($data);
        if ($post) {
            $target = SuperAdmin::verifyFromDb(session('user_id')) ? '/admin' : 'post/index';
            $this->success('帖子发布成功', $target);
        }
        $this->error('帖子发布失败');
    }

    public function edit($id)
    {
        try {
            if (!session('user_id')) {
                $this->redirect('login');
            }

            $post = Post::find($id);
            if (!$post) {
                $this->error('帖子不存在');
            }

            if ($post->user_id != session('user_id')) {
                $this->error('无权限编辑此帖子');
            }

            $this->assign('post', $post);
            return $this->fetch('post/edit');
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        if (!session('user_id')) {
            $this->redirect('login');
        }

        $post = Post::find($id);
        if (!$post) {
            $this->error('帖子不存在');
        }

        if ($post->user_id != session('user_id')) {
            $this->error('无权限编辑此帖子');
        }

        $data = $request->only(['title', 'content']);
        $validate = $this->validate($data, [
            'title|标题' => 'require|max:255',
            'content|内容' => 'require',
        ]);

        if ($validate !== true) {
            $this->error($validate);
        }

        $data['title'] = htmlspecialchars($data['title']);
        $data['content'] = htmlspecialchars($data['content']);

        $result = $post->save($data);
        if ($result) {
            $this->success('帖子更新成功', 'post/detail?id=' . $id);
        }
        $this->error('帖子更新失败');
    }

    public function delete($id)
    {
        if (!session('user_id')) {
            $this->redirect('login');
        }

        $t = $this->request->get('csrf', '');
        if ($t === '' || $t !== session('csrf_token')) {
            $this->error('请求无效或已过期，请刷新页面后重试');
        }

        $userId = session('user_id');
        $isSuper = SuperAdmin::verifyFromDb($userId);

        $post = Post::find($id);
        if (!$post) {
            $this->error('帖子不存在');
        }

        if (!$isSuper && (int) $post->user_id !== (int) $userId) {
            $this->error('无权限删除此帖子');
        }

        $result = $post->delete();
        if ($result) {
            $target = $isSuper ? '/admin' : 'post/index';
            $this->success('帖子删除成功', $target);
        }
        $this->error('帖子删除失败');
    }
}
