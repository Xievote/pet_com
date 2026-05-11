<?php
namespace app\index\model;

use think\Model;

class Bookmark extends Model
{
    protected $table = 'bookmarks';

    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = false;

    protected $type = [
        'created_at' => 'datetime',
    ];

    protected $field = [
        'user_id',
        'target_type',
        'target_id',
        'folder',
    ];

    public function user()
    {
        return $this->belongsTo('app\index\model\User', 'user_id', 'id');
    }
}