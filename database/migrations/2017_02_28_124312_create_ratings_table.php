<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->morphs('ratingable');
            $table->boolean('rating_1')->default(0)->comment('评为1分');
            $table->boolean('rating_2')->default(0)->comment('评为2分');
            $table->boolean('rating_3')->default(0)->comment('评为3分');
            $table->boolean('rating_4')->default(0)->comment('评为4分');
            $table->boolean('rating_5')->default(0)->comment('评为5分');
            $table->timestamps();

            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rates');
    }
}
