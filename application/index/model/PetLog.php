<?php
namespace app\index\model;
use think\Model;

class PetLog extends Model
{
    protected $table = 'pet_logs';
    protected $autoWriteTimestamp = true;
    protected $type = [
        'create_time' => 'datetime',
    ];
    
    // 关联用户
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id');
    }
}