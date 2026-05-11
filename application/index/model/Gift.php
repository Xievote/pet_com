<?php
namespace app\index\model;

use think\Model;

class Gift extends Model
{
    protected $table = 'gifts';

    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = false;

    protected $type = [
        'created_at' => 'datetime',
    ];

    protected $field = [
        'from_user_id',
        'to_user_id',
        'gift_type',
        'price',
        'target_type',
        'target_id',
        'message',
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