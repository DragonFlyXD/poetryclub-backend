<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppreciatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appreciates', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->unsignedInteger('appreciation_id')->comment('赏析ID');
            $table->timestamps();

            $table->index(['user_id', 'appreciation_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appreciates');
    }
}
