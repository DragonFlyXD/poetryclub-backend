<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->string('name', 20)->comment('用户名');
            $table->string('email', 50)->nullable()->comment('邮箱地址');
            $table->char('mobile', 11)->nullable()->comment('手机号码');
            $table->string('password')->comment('密码');
            $table->string('avatar')->comment('头像地址');
            $table->char('confirmation_token', 40)->comment('邮箱token');
            $table->boolean('is_active')->default(0)->comment('激活状态');
//            $table->nullableMorphs('social');
            $table->unsignedBigInteger('social_id')->nullable()->comment('第三方账号ID');
            $table->string('social_type')->nullable()->comment('第三方登录类型');
            $table->unsignedTinyInteger('role_level')->default(1)->comment('角色等级');
            $table->unsignedInteger('works_count')->default(0)->comment('作品总数');
            $table->unsignedInteger('favorites_count')->default(0)->comment('收藏总数');
            $table->unsignedInteger('likes_count')->default(0)->comment('被点赞总数');
            $table->unsignedInteger('comments_count')->default(0)->comment('评论总数');
            $table->unsignedInteger('shares_count')->default(0)->comment('分享总数');
            $table->unsignedInteger('followers_count')->default(0)->comment('关注总数');
            $table->unsignedInteger('followings_count')->default(0)->comment('被关注总数');
            $table->unsignedBigInteger('ratings_count')->default(0)->comment('评分总数');
            $table->rememberToken()->comment('记住用户名');
            $table->timestamps();

            $table->unique(['name', 'email', 'mobile']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
