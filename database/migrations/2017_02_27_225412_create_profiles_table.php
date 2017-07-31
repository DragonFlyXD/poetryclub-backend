<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->string('nickname', 20)->nullable()->comment('昵称');
            $table->unsignedTinyInteger('gender')->default(0)->comment('性别 1>男 2>女');
            $table->date('birthday')->nullable()->comment('生日');
            $table->string('signature', 140)->nullable()->comment('私人语录');
            $table->string('location', 50)->nullable()->comment('居住地');
            $table->string('occupation', 50)->nullable()->comment('职业');
            $table->string('bio', 400)->nullable()->comment('个人简介');
            $table->string('poet', 50)->nullable()->comment('最钟爱的诗人');
            $table->boolean('is_hidden')->default(0)->comment('是否隐藏该个人信息');
            $table->timestamps();

            $table->index(['user_id']);
            $table->unique(['nickname']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
