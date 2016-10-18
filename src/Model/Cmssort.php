<?php

namespace Zank\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 用户模型.
 *
 * @author Seven Du<lovevipdsw@outlook.com>
 **/
class Cmssort extends Model
{
    use SoftDeletes;

    protected $table = 'cms_sort';

    protected $primaryKey = 'sort_id';
    /**
     * 在数组中显示的属性
     *
     * @var array
     */
    protected $visible = ['sort_id', 'sort_name','sort_pingyin','cid','sort_icon'];

    /**
     * 用于设置查询条件为cms_id的快捷方法.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query 查询构造器
     * @param string|int                            $cmsid 条件字符串
     *
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * @author Seven Du <lovevipdsw@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByNullDeleted(Builder $query): Builder
    {   

        return $query
                ->whereNull('deleted_at');


    }

    public function scopeByNullStatus(Builder $query): Builder
    {   

        return $query
                ->where('status',0);

    }

    public function scopeOrderByCid(Builder $query): Builder
    {
        return $query
                ->orderBy('cid','asc');
    }
    
    public static function getAllSort()
    {

        return self::ByNullDeleted()
                        ->ByNullStatus()
                        ->OrderByCid()
                        ->get();

    }
} // END class User extends Eloquent
