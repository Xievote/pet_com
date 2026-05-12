<?php
namespace app\index\model;

use think\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = false;

    protected $type = [
        'created_at' => 'datetime',
        'is_read' => 'integer',
    ];

    protected $field = [
        'user_id',
        'type',
        'from_user_id',
        'target_type',
        'target_id',
        'content',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo('app\index\model\User', 'user_id', 'id');
    }

    public function fromUser()
    {
        return $this->belongsTo('app\index\model\User', 'from_user_id', 'id');
    }
}
