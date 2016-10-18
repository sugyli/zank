<?php

use Illuminate\Database\Schema\Blueprint;

return function (Blueprint $table) {
    $table->increments('cms_id');
    $table->tinyInteger('sort_id');
    $table->integer('post_uid'); // 发布者ID
    $table->string('title'); 
    $table->mediumText('content');
    $table->integer('reply_count')->nullable()->default(0); //回复数
    $table->integer('read_count')->nullable()->default(0); //浏览数
    $table->integer('last_reply_uid')->nullable()->default(0); //最后回复人ID
    $table->integer('last_reply_time')->nullable()->default(0); //最后回复时间
    $table->tinyInteger('digest')->nullable()->default(0);//是否精华 0否 1是
    $table->tinyInteger('top')->nullable()->default(0);//是否置顶 0否 1分类中 2全局
    $table->tinyInteger('lock')->nullable()->default(0);//锁文（是否允许回复) 0否 1是
    $table->tinyInteger('recommend')->nullable()->default(0);//是否设置推荐 0否 1是
    $table->integer('recommend_time')->nullable()->default(0);//设置推荐时间
    $table->tinyInteger('is_del')->nullable()->default(0);//是否以删除  0否 1是
    $table->integer('reply_all_count')->nullable()->default(0); //全部评论数
    $table->string('attach')->nullable();//附件 
    $table->integer('praise')->nullable()->default(0);//喜欢
    $table->tinyInteger('from')->nullable()->default(0);//客户端类型 0网站 1手机网页 2安卓 3苹果 4其他
    $table->timestamps();//添加 created_at 和 updated_at列.
    $table->softDeletes();//新增一个 deleted_at 列 用于软删除.
    $table->index(['deleted_at','updated_at']);
    $table->index(['recommend_time', 'sort_id','recommend','deleted_at']);//推荐时间 分类 是否设置推荐 是否删除 为索引
};
