<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\PetLog;
use app\index\model\Post;
use think\Request;

class Pet extends Controller
{
    protected $method = [];
    // 1. 显示列表页（读取数据）
    public function index()
    {
        // 查询所有宠物记录，按时间倒序，关联用户信息
        $logs = PetLog::with('user')->order('id', 'desc')->select();
        
        // 查询所有帖子，按时间倒序，关联用户信息
        $posts = Post::with('user')->order('created_at', 'desc')->select();
        
        // 把数据传给视图
        return $this->fetch('index', ['logs' => $logs, 'posts' => $posts]);
    }

    // 2. 保存数据（写入数据库）
    public function save(Request $request)
    {
        $userid = session('user_id');
        if(!$userid){
            return $this->error('请先登录', 'login');
        }

        // 获取表单提交的数据
        $data = $request->post();
        $data['user_id'] = $userid;
        
        // 简单验证
        if(empty($data['pet_name']) || empty($data['content'])) {
            return $this->error('名字和内容不能为空');
        }

        // 处理文件上传
        $file = $request->file('image');
        if($file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 获取相对路径，例如 20260421/abc.jpg
                $data['image'] = '/uploads/' . $info->getSaveName();
            }else{
                return $this->error($file->getError());
            }
        }

        // 保存数据
        $petLog = new PetLog;
        $petLog->pet_name = $data['pet_name'];
        $petLog->content = $data['content'];
        $petLog->user_id = $data['user_id'];
        if(isset($data['image'])) {
            $petLog->image = $data['image'];
        }
        $petLog->create_time = time();

        if($petLog->save()){
            return $this->success('发布成功', 'index');
        }
        return $this->error('发布失败');
    }

}