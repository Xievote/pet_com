<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use app\index\model\PetProfile;
use app\index\model\PetPhoto;
use app\index\model\PetLog;
use app\index\model\User;
use app\index\model\Post;
use app\index\model\Comment;

class PetProfileController extends Controller
{
    /**
     * 宠物档案首页
     */
    public function index(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return $this->error('请先登录', '/login');
        }
        
        // 获取当前用户的所有宠物档案
        $petProfiles = PetProfile::where('user_id', $userId)->select();
        
        $this->assign('pet_profiles', $petProfiles);
        $this->assign('user_id', $userId);
        
        return $this->fetch('pet/profile_index');
    }
    
    /**
     * 宠物档案详情页
     */
    public function detail($id)
    {
        $userId = session('user_id');
        if (!$userId) {
            return $this->error('请先登录', '/login');
        }
        
        // 获取宠物档案
        $petProfile = PetProfile::where('id', $id)->where('user_id', $userId)->find();
        if (!$petProfile) {
            return $this->error('宠物档案不存在或无权限访问');
        }
        
        // 获取宠物的照片墙
        $photos = PetPhoto::where('pet_profile_id', $id)->order('created_at', 'desc')->select();
        
        // 获取宠物的生活记录（时间线）
        $petLogs = PetLog::where('pet_profile_id', $id)
            ->order('create_time', 'desc')
            ->paginate(10);
            
        $this->assign('pet_profile', $petProfile);
        $this->assign('photos', $photos);
        $this->assign('pet_logs', $petLogs);
        
        return $this->fetch('pet/profile_detail');
    }
    
    /**
     * 创建宠物档案
     */
    public function create(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }
        
        if ($request->isPost()) {
            $data = $request->post();
            
            // 验证数据
            if (empty($data['name'])) {
                return json(['code' => 400, 'msg' => '宠物姓名不能为空']);
            }
            
            if (strlen($data['name']) > 50) {
                return json(['code' => 400, 'msg' => '宠物姓名不能超过50个字符']);
            }
            
            // 创建宠物档案
            $petProfile = new PetProfile();
            $petProfile->user_id = $userId;
            $petProfile->name = trim($data['name']);
            $petProfile->breed = isset($data['breed']) ? trim($data['breed']) : '';
            $petProfile->birthday = isset($data['birthday']) ? trim($data['birthday']) : null;
            $petProfile->gender = isset($data['gender']) ? trim($data['gender']) : 'unknown';
            $petProfile->hobbies = isset($data['hobbies']) ? trim($data['hobbies']) : '';
            
            try {
                if ($petProfile->save()) {
                    return json(['code' => 200, 'msg' => '宠物档案创建成功', 'data' => $petProfile]);
                } else {
                    return json(['code' => 500, 'msg' => '创建失败']);
                }
            } catch (\Exception $e) {
                return json(['code' => 500, 'msg' => '创建失败：' . $e->getMessage()]);
            }
        }
        
        return json(['code' => 405, 'msg' => '请求方法不支持']);
    }
    
    /**
     * 更新宠物档案
     */
    public function update(Request $request, $id)
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }
        
        // 检查宠物档案是否存在且属于当前用户
        $petProfile = PetProfile::where('id', $id)->where('user_id', $userId)->find();
        if (!$petProfile) {
            return json(['code' => 404, 'msg' => '宠物档案不存在或无权限访问']);
        }
        
        if ($request->isPost()) {
            $data = $request->post();
            
            // 验证数据
            if (empty($data['name'])) {
                return json(['code' => 400, 'msg' => '宠物姓名不能为空']);
            }
            
            if (strlen($data['name']) > 50) {
                return json(['code' => 400, 'msg' => '宠物姓名不能超过50个字符']);
            }
            
            // 更新宠物档案
            $petProfile->name = trim($data['name']);
            $petProfile->breed = isset($data['breed']) ? trim($data['breed']) : '';
            $petProfile->birthday = isset($data['birthday']) ? trim($data['birthday']) : null;
            $petProfile->gender = isset($data['gender']) ? trim($data['gender']) : 'unknown';
            $petProfile->hobbies = isset($data['hobbies']) ? trim($data['hobbies']) : '';
            
            try {
                if ($petProfile->save()) {
                    return json(['code' => 200, 'msg' => '宠物档案更新成功', 'data' => $petProfile]);
                } else {
                    return json(['code' => 500, 'msg' => '更新失败']);
                }
            } catch (\Exception $e) {
                return json(['code' => 500, 'msg' => '更新失败：' . $e->getMessage()]);
            }
        }
        
        return json(['code' => 405, 'msg' => '请求方法不支持']);
    }
    
    /**
     * 删除宠物档案
     */
    public function delete($id)
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }
        
        // 检查宠物档案是否存在且属于当前用户
        $petProfile = PetProfile::where('id', $id)->where('user_id', $userId)->find();
        if (!$petProfile) {
            return json(['code' => 404, 'msg' => '宠物档案不存在或无权限访问']);
        }
        
        try {
            // 删除宠物档案
            if ($petProfile->delete()) {
                return json(['code' => 200, 'msg' => '宠物档案删除成功']);
            } else {
                return json(['code' => 500, 'msg' => '删除失败']);
            }
        } catch (\Exception $e) {
            return json(['code' => 500, 'msg' => '删除失败：' . $e->getMessage()]);
        }
    }
    
    /**
     * 上传宠物头像
     */
    public function uploadAvatar(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }
        
        $petId = $request->param('pet_id');
        if (!$petId) {
            return json(['code' => 400, 'msg' => '宠物ID不能为空']);
        }
        
        // 检查宠物档案是否存在且属于当前用户
        $petProfile = PetProfile::where('id', $petId)->where('user_id', $userId)->find();
        if (!$petProfile) {
            return json(['code' => 404, 'msg' => '宠物档案不存在或无权限访问']);
        }
        
        $avatarFile = $request->file('avatar');
        if (!$avatarFile) {
            return json(['code' => 400, 'msg' => '请选择头像文件']);
        }
        
        // 验证文件格式和大小
        if (!$avatarFile->check(['size' => 5242880, 'ext' => 'jpg,png,jpeg'])) {
            return json(['code' => 400, 'msg' => '头像文件格式或大小不正确']);
        }
        
        // 保存文件
        $info = $avatarFile->validate([
            'size' => 5242880,
            'ext' => 'jpg,png,jpeg',
        ])->move('uploads/pets');
        
        if (!$info) {
            return json(['code' => 400, 'msg' => '头像上传失败：' . $avatarFile->getError()]);
        }
        
        // 更新宠物档案头像
        $petProfile->avatar = '/uploads/pets/' . $info->getSaveName();
        
        try {
            if ($petProfile->save()) {
                return json(['code' => 200, 'msg' => '头像上传成功', 'data' => $petProfile->avatar]);
            } else {
                return json(['code' => 500, 'msg' => '头像更新失败']);
            }
        } catch (\Exception $e) {
            return json(['code' => 500, 'msg' => '头像更新失败：' . $e->getMessage()]);
        }
    }
    
    /**
     * 获取宠物档案列表（用于API）
     */
    public function getList()
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }
        
        $petProfiles = PetProfile::where('user_id', $userId)->select();
        
        return json(['code' => 200, 'data' => $petProfiles]);
    }
    
    /**
     * 萌宠排行榜页面
     */
    public function ranking()
    {
        $userId = session('user_id');
        if (!$userId) {
            return $this->error('请先登录', '/login');
        }
        
        return $this->fetch('pet/ranking');
    }
    
    /**
     * 获取萌宠排行榜
     */
    public function getRanking($type = 'weekly')
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }
        
        $limit = 20; // 排行榜显示前20名
        
        try {
            // 根据类型选择时间范围
            $timeCondition = '';
            $timeParams = [];
            
            switch ($type) {
                case 'daily':
                    $timeCondition = 'AND DATE(pl.create_time) = CURDATE()';
                    break;
                case 'weekly':
                    $timeCondition = 'AND YEARWEEK(pl.create_time, 1) = YEARWEEK(CURDATE(), 1)';
                    break;
                case 'monthly':
                    $timeCondition = 'AND YEAR(pl.create_time) = YEAR(CURDATE()) AND MONTH(pl.create_time) = MONTH(CURDATE())';
                    break;
                default:
                    $timeCondition = ''; // 全部时间
            }
            
            // 使用ThinkPHP查询构建器来构建更可靠的查询
            $rankings = \think\Db::name('pet_profiles')
                ->alias('pp')
                ->leftJoin('pet_logs pl', 'pp.id = pl.pet_profile_id')
                ->leftJoin('comments c', 'pp.id = (SELECT pet_profile_id FROM pet_logs WHERE id = c.pet_log_id)')
                ->leftJoin('likes l', '(l.target_type = "pet_log" AND l.target_id = pl.id) OR (l.target_type = "post" AND l.target_id = (SELECT post_id FROM comments WHERE id = c.id))')
                ->where('pp.user_id', 'is not null')
                ->group('pp.id')
                ->field([
                    'pp.id',
                    'pp.name',
                    'pp.avatar',
                    'pp.charm_score',
                    'COUNT(pl.id) as post_count',
                    'COUNT(DISTINCT c.id) as comment_count',
                    'COUNT(DISTINCT l.id) as like_count',
                    '(COUNT(pl.id) * 0.3 + COUNT(DISTINCT c.id) * 0.2 + COUNT(DISTINCT l.id) * 0.5) as calculated_charm_score'
                ])
                ->having('calculated_charm_score > 0 OR post_count > 0 OR comment_count > 0 OR like_count > 0')
                ->order('calculated_charm_score DESC, pp.charm_score DESC')
                ->limit($limit)
                ->select();
            
            // 如果没有数据，返回空数组而不是报错
            if (empty($rankings)) {
                $rankings = [];
            }
            
            return json(['code' => 200, 'data' => $rankings, 'type' => $type]);
        } catch (\Exception $e) {
            return json(['code' => 500, 'msg' => '获取排行榜失败：' . $e->getMessage()]);
        }
    }
    
    /**
     * 获取宠物档案的萌力值计算详情
     */
    public function getCharmDetails($id)
    {
        $userId = session('user_id');
        if (!$userId) {
            return json(['code' => 401, 'msg' => '请先登录']);
        }
        
        // 检查宠物档案是否存在且属于当前用户
        $petProfile = PetProfile::where('id', $id)->where('user_id', $userId)->find();
        if (!$petProfile) {
            return json(['code' => 404, 'msg' => '宠物档案不存在或无权限访问']);
        }
        
        try {
            // 计算各项指标
            $postData = PetLog::where('pet_profile_id', $id)->count();
            $commentData = Comment::alias('c')
                ->join('pet_logs pl', 'c.pet_log_id = pl.id')
                ->where('pl.pet_profile_id', $id)
                ->count();
            $likeData = \think\Db::name('likes')
                ->where('target_type', 'pet_log')
                ->where('target_id', 'IN', function($query) use ($id) {
                    $query->table('pet_logs')->where('pet_profile_id', $id)->field('id');
                })
                ->count();
                
            $totalCharm = $postData * 0.3 + $commentData * 0.2 + $likeData * 0.5;
            
            return json([
                'code' => 200, 
                'data' => [
                    'pet_profile' => $petProfile,
                    'post_count' => $postData,
                    'comment_count' => $commentData,
                    'like_count' => $likeData,
                    'calculated_charm' => round($totalCharm, 1),
                    'actual_charm' => $petProfile->charm_score
                ]
            ]);
        } catch (\Exception $e) {
            return json(['code' => 500, 'msg' => '获取详情失败：' . $e->getMessage()]);
        }
    }
}