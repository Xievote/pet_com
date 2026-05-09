<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\PetLog;
use app\index\model\Post;
use think\Request;
use app\common\SuperAdmin;

class Pet extends Controller
{
    protected $method = [];

    public function index(Request $request)
    {
        if (SuperAdmin::verifyFromDb(session('user_id'))) {
            $this->redirect('/admin');
        }
        return $this->renderHome($request, '/index', false);
    }

    /**
     * 超级管理员主页（与普通主页同一套模板与数据，URL 独立）。
     */
    public function adminIndex(Request $request)
    {
        if (!session('user_id')) {
            $this->redirect('/login');
        }
        if (!SuperAdmin::verifyFromDb(session('user_id'))) {
            $this->redirect('/index');
        }
        return $this->renderHome($request, '/admin', true);
    }

    /**
     * @param string $homePath 分页与表单所在路径前缀
     * @param bool $canModerateAll 是否可删任意宠物记录/帖子（仍受服务端 DB 校验）
     */
    protected function renderHome(Request $request, $homePath, $canModerateAll)
    {
        $logPage = $request->param('log_page', 1);
        $postPage = $request->param('post_page', 1);

        $logs = PetLog::order('id', 'desc')->paginate(5, false, ['page' => $logPage, 'query' => ['log_page' => $logPage, 'post_page' => $postPage]]);
        $posts = Post::order('created_at', 'desc')->paginate(5, false, ['page' => $postPage, 'query' => ['post_page' => $postPage, 'log_page' => $logPage]]);

        $userIds = [];
        foreach ($logs as $log) {
            $userIds[] = $log['user_id'];
        }
        foreach ($posts as $post) {
            $userIds[] = $post['user_id'];
        }
        $userIds = array_unique($userIds);

        $users = [];
        if (!empty($userIds)) {
            $userModel = new \app\index\model\User();
            $userList = $userModel->where('id', 'in', $userIds)->column('username,avatar', 'id');
            foreach ($userList as $id => $user) {
                $users[$id] = $user;
            }
        }

        $csrf = bin2hex(random_bytes(16));
        session('csrf_token', $csrf);

        $currentAvatar = '';
        $uid = session('user_id');
        if ($uid) {
            $me = \app\index\model\User::field('avatar')->find($uid);
            if ($me && !empty(trim((string) $me->avatar))) {
                $currentAvatar = $me->avatar;
            }
        }

        return $this->fetch('index', [
            'logs' => $logs,
            'posts' => $posts,
            'users' => $users,
            'csrf_token' => $csrf,
            'home_path' => $homePath,
            'can_moderate_all' => $canModerateAll,
            'current_avatar' => $currentAvatar,
        ]);
    }

    public function save(Request $request)
    {
        $userid = session('user_id');
        if (!$userid) {
            return $this->error('请先登录', 'login');
        }
        $posted = $request->post('csrf_token', '');
        if ($posted === '' || $posted !== session('csrf_token')) {
            return $this->error('请求无效或已过期，请刷新页面后重试');
        }

        $data = $request->post();
        $data['user_id'] = $userid;

        if (empty($data['pet_name']) || empty($data['content'])) {
            return $this->error('名字和内容不能为空');
        }

        $file = $request->file('image');
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                $data['image'] = '/uploads/' . $info->getSaveName();
            } else {
                return $this->error($file->getError());
            }
        }

        $petLog = new PetLog;
        $petLog->pet_name = $data['pet_name'];
        $petLog->content = $data['content'];
        $petLog->user_id = $data['user_id'];
        if (isset($data['image'])) {
            $petLog->image = $data['image'];
        }
        $petLog->create_time = time();

        if ($petLog->save()) {
            $target = SuperAdmin::verifyFromDb($userid) ? '/admin' : '/index';
            return $this->success('发布成功', $target);
        }
        return $this->error('发布失败');
    }

    public function deleteLog($id)
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录'], 401);
        }

        $token = $this->request->header('x-csrf-token');
        if ($token === null || $token === '' || $token !== session('csrf_token')) {
            return json(['code' => 403, 'msg' => '请求无效或已过期，请刷新页面后重试'], 403);
        }

        $isAdmin = SuperAdmin::verifyFromDb($userId);

        try {
            $log = PetLog::get($id);
            if (!$log) {
                return json(['code' => 404, 'msg' => '记录不存在'], 404);
            }
            if (!$isAdmin && (int) $log->user_id !== (int) $userId) {
                return json(['code' => 403, 'msg' => '无权删除此记录'], 403);
            }

            if ($log->delete()) {
                return json(['code' => 200, 'msg' => '删除成功']);
            }
            return json(['code' => 500, 'msg' => '删除失败'], 500);
        } catch (\Exception $e) {
            return json(['code' => 500, 'msg' => '删除失败：' . $e->getMessage()], 500);
        }
    }
}
