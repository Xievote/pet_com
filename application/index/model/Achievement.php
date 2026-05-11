<?php
namespace app\index\model;

use think\Model;

class Achievement extends Model
{
    protected $table = 'achievements';

    protected $autoWriteTimestamp = false;

    protected $field = [
        'code',
        'name',
        'description',
        'icon',
        'condition_type',
        'condition_value',
        'sort_order',
    ];
}