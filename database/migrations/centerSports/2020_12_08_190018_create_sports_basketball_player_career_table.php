<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballPlayerCareerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_player_career', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('player_id')->comment('球员id');
            $table->unsignedInteger('team_id')->comment('球队id');
            $table->string('season')->nullable()->comment('赛季年份');
            $table->unsignedSmallInteger('years')->nullable()->comment('年份');
            $table->unsignedInteger('updated_time')->nullable()->comment('纳米更新时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sports_basketball_player_career');
    }
}
