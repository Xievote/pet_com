<?php
namespace app\index\model;

use think\Model;

class PetPhoto extends Model
{
    protected $table = 'pet_photos';
    
    // 开启自动写入时间戳
    protected $autoWriteTimestamp = true;
    
    // 设置时间字段类型为datetime
    protected $type = [
        'created_at' => 'datetime',
    ];
    
    // 允许批量赋值的字段
    protected $field = [
        'pet_profile_id',
        'image_path',
        'description'
    ];
    
    /**
     * 关联宠物档案
     */
    public function petProfile()
    {
        return $this->belongsTo('app\index\model\PetProfile', 'pet_profile_id', 'id');
    }
}