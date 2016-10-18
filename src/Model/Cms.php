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
class Cms extends Model
{
    use SoftDeletes;

    protected $table = 'cms';

    protected $primaryKey = 'cms_id';
    protected $hidden = ['deleted_at'];

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

    public function  scopeOrderByUpdated_at(Builder $query): Builder
    {
        return $query
                ->orderBy('updated_at','desc');

    }

    public static function pageData(int $page = 1 ,int $pagesize = 15)
    {

        $total = self::counts();
        if($total>0)
        {
            //计算总页数
            $pagenum = ceil( $total / $pagesize );//当没有数据的时候 计算出来为0
            if ($page > $pagenum)
            {
                $page = $pagenum;//分页越界
            } 
            //下一页开始的ID (0)开始
            $offset = ($page - 1) * $pagesize;

            return  self::ByNullDeleted()
                            ->OrderByUpdated_at()
                            ->skip($offset)   //偏移(Offset)
                            ->take($pagesize)   //限制(Limit)
                            ->get();

        }

        return null;

    }

    public static function counts()
    {

        return self::ByNullDeleted()->count();

    }

   
} // END class User extends Eloquent
