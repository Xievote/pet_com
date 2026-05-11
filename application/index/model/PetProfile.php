<?php
namespace app\index\model;

use think\Model;

class PetProfile extends Model
{
    protected $table = 'pet_profiles';
    
    // 开启自动写入时间戳
    protected $autoWriteTimestamp = true;
    
    // 设置时间字段类型为datetime
    protected $type = [
        'birthday' => 'date',
        'charm_score' => 'float',
    ];
    
    // 允许批量赋值的字段
    protected $field = [
        'user_id',
        'name',
        'breed',
        'birthday',
        'gender',
        'avatar',
        'cover',
        'hobbies',
        'charm_score'
    ];
    
    /**
     * 关联用户
     */
    public function user()
    {
        return $this->belongsTo('app\index\model\User', 'user_id', 'id');
    }
    
    /**
     * 关联宠物照片
     */
    public function photos()
    {
        return $this->hasMany('app\index\model\PetPhoto', 'pet_profile_id', 'id');
    }
    
    /**
     * 获取宠物的萌力值
     */
    public function getCharmScoreAttribute($value)
    {
        return $value ?: 0.0;
    }
    
    /**
     * 设置宠物的萌力值
     */
    public function setCharmScoreAttribute($value)
    {
        $this->attributes['charm_score'] = number_format($value, 1, '.', '');
    }
}