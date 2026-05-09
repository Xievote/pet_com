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
    public function index(Request $request)
    {
        // 获取页码，默认为1
        $logPage = $request->param('log_page', 1);
        $postPage = $request->param('post_page', 1);
        
        // 查询宠物记录，按时间倒序，每页5条
        $logs = PetLog::order('id', 'desc')->paginate(5, false, ['page' => $logPage, 'query' => ['log_page' => $logPage]]);
        
        // 查询帖子，按时间倒序，每页5条
        $posts = Post::order('created_at', 'desc')->paginate(5, false, ['page' => $postPage, 'query' => ['post_page' => $postPage]]);
        
        // 获取所有用户ID
        $userIds = [];
        foreach ($logs as $log) {
            $userIds[] = $log['user_id'];
        }
        foreach ($posts as $post) {
            $userIds[] = $post['user_id'];
        }
        $userIds = array_unique($userIds);
        
        // 查询用户信息
        $users = [];
        if (!empty($userIds)) {
            $userModel = new \app\index\model\User();
            $userList = $userModel->where('id', 'in', $userIds)->column('username,avatar', 'id');
            foreach ($userList as $id => $user) {
                $users[$id] = $user;
            }
        }
        
        // 把数据传给视图
        return $this->fetch('index', [
            'logs' => $logs, 
            'posts' => $posts,
            'users' => $users
        ]);
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

    // 3. 删除宠物记录（RESTful API - DELETE方法）
    public function deleteLog($id)
    {
        // 检查登录状态
        $userId = session('user_id');
        if (!$userId) {
            return json([
                'code' => 401,
                'msg' => '请先登录'
            ], 401);
        }

        try {
            // 查找记录
            $log = PetLog::get($id);
            
            // 验证记录是否存在
            if (!$log) {
                return json([
                    'code' => 404,
                    'msg' => '记录不存在'
                ], 404);
            }
            
            // 验证用户权限（只能删除自己发布的记录）
            if ($log->user_id != $userId) {
                return json([
                    'code' => 403,
                    'msg' => '无权删除此记录'
                ], 403);
            }
            
            // 执行删除操作
            if ($log->delete()) {
                return json([
                    'code' => 200,
                    'msg' => '删除成功'
                ]);
            } else {
                return json([
                    'code' => 500,
                    'msg' => '删除失败'
                ], 500);
            }
        } catch (\Exception $e) {
            // 异常处理
            return json([
                'code' => 500,
                'msg' => '删除失败：' . $e->getMessage()
            ], 500);
        }
    }

}