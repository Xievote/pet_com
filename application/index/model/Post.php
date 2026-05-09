<?php
namespace app\index\model;
use think\Model;

class Post extends Model
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
        return $this->belongsTo('app\index\model\User', 'user_id', 'id');
    }
    
    // 关联评论
    public function comments()
    {
        return $this->hasMany('Comment', 'post_id', 'id');
    }
}
