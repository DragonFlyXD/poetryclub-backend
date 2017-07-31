<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->unsignedInteger('from_user_id')->comment('发送私信的用户ID');
            $table->unsignedInteger('to_user_id')->comment('接收私信的用户ID');
            $table->bigInteger('dialog_id')->comment('对话ID');
            $table->text('body')->comment('私信内容');
            $table->timestamp('read_at')->nullable()->comment('阅读私信内容的时间');
            $table->boolean('from_user_deleted')->default(0)->comment('发送者删除私信');
            $table->boolean('to_user_deleted')->default(0)->comment('接受者删除私信');
            $table->boolean('is_reply')->default(1)->comment('是否可以回复');
            $table->timestamps();

            $table->index(['from_user_id', 'to_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
