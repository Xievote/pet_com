<?php
namespace app\index\model;
use think\Model;

class Comment extends Model
{
    // 开启自动写入时间戳
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    
    // 设置时间字段类型为datetime
    protected $type = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // 关联用户
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id');
    }
    
    // 关联帖子
    public function post()
    {
        return $this->belongsTo('Post', 'post_id', 'id');
    }
    
    // 关联父评论
    public function parent()
    {
        return $this->belongsTo('Comment', 'parent_id', 'id');
    }
    
    // 关联子评论
    public function children()
    {
        return $this->hasMany('Comment', 'parent_id', 'id');
    }
}
