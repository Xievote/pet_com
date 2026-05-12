<?php
namespace app\index\model;

use think\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = false;

    protected $type = [
        'created_at' => 'datetime',
        'is_read' => 'integer',
    ];

    protected $field = [
        'from_user_id',
        'to_user_id',
        'content',
        'msg_type',
        'is_read',
    ];

    public function fromUser()
    {
        return $this->belongsTo('app\index\model\User', 'from_user_id', 'id');
    }

    public function toUser()
    {
        return $this->belongsTo('app\index\model\User', 'to_user_id', 'id');
    }
}
