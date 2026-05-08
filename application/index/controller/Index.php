<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\PetLog;
use app\index\model\Post;
class Index extends Controller
{
    public function index()
    {
        // 1. 给宠物记录列表 $logs 传测试数据
        $logs = PetLog::order('create_time', 'desc')->select();

        // 2. 给社区帖子列表 $posts 传测试数据
        $posts = Post::order('created_at','desc')->select();

        // 3. 把两个变量都传给模板
        $this->assign('logs', $logs);
        $this->assign('posts', $posts);

        // 4. 渲染你的主界面模板
        return $this->fetch('pet/index');
    }
}