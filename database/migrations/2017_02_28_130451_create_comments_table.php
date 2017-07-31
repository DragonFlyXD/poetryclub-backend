<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->unsignedInteger('parent_id')->nullable()->comment('父级评论ID');
            $table->string('body', 140)->comment('内容');
            $table->morphs('commentable');
            $table->unsignedInteger('likes_count')->default(0)->comment('被点赞总数');
            $table->unsignedTinyInteger('level')->default(1)->comment('评论层级');
            $table->boolean('is_hidden')->default(0)->comment('是否隐藏该评论');
            $table->timestamps();

            $table->index(['user_id', 'parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
