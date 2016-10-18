<?php

use Illuminate\Database\Schema\Blueprint;

return function (Blueprint $table) {
    $table->increments('sort_id');
    $table->string('sort_name');
    $table->string('sort_pingyin', 1000);
    $table->string('sort_icon', 150);
    $table->tinyInteger('cid')->nullable()->default(0);
    $table->tinyInteger('who_can_post')->nullable()->default(0);//能发帖的人 0所有  1 特定
    $table->tinyInteger('who_can_reply')->nullable()->default(0);//能回帖的人 0所有  1 特定
    $table->integer('thread_count')->nullable()->default(0); //帖子数
    $table->tinyInteger('recommend')->nullable()->default(0);//是否推荐 0否  1 是
    $table->tinyInteger('status')->nullable()->default(0);//是否隐藏 0否  1 是
    $table->timestamps();//添加 created_at 和 updated_at列.
    $table->softDeletes();//新增一个 deleted_at 列 用于软删除.
    $table->index('cid');
    $table->index(['deleted_at', 'recommend','status']);//是删除  是否设置推荐 是否隐藏 为索引
};
