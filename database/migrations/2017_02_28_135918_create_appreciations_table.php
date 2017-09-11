<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppreciationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appreciations', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->unsignedInteger('poem_id')->comment('诗文ID');
            $table->unsignedInteger('category_id')->default(0)->comment('分类ID');
            $table->string('title', 50)->comment('标题');
            $table->text('body')->comment('内容');
            $table->string('summary',450)->comment('摘要');
            $table->unsignedInteger('pageviews_count')->default(0)->comment('浏览量总数');
            $table->unsignedInteger('comments_count')->default(0)->comment('评论总数');
            $table->unsignedInteger('favorites_count')->default(0)->comment('收藏总数');
            $table->unsignedInteger('appreciations_count')->default(0)->commment('赏析总数');
            $table->unsignedInteger('shares_count')->default(0)->comment('分享总数');
            $table->unsignedInteger('likes_count')->default(0)->comment('被点赞总数');
            $table->unsignedInteger('ratings_count')->default(0)->comment('评分次数');
            $table->boolean('is_original')->default(1)->comment('是否为原创诗文');
            $table->boolean('is_valid')->default(1)->comment('是否已发布');
            $table->boolean('close_comment')->default(0)->comment('是否关闭评论');
            $table->boolean('is_hidden')->default(0)->comment('是否隐藏该诗文');
            $table->timestamps();

            $table->index(['user_id', 'poem_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appreciations');
    }
}
