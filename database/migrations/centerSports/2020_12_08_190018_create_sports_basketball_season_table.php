<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballSeasonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_season', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary()->comment('赛季id');
            $table->unsignedInteger('competition_id')->index('idx_competition_id')->comment('赛事id');
            $table->string('year')->default('')->comment('年份');
            $table->boolean('has_player_stats')->unsigned()->nullable()->index('idx_has_player_stats')->comment('是否有球员统计，1-是、0-否');
            $table->boolean('has_team_stats')->unsigned()->nullable()->index('idx_has_team_stats')->comment('是否有球队统计，1-是、0-否');
            $table->boolean('is_current')->unsigned()->nullable()->index('idx_is_current')->comment('是否最新赛季，1-是、0-否');
            $table->boolean('is_deleted')->unsigned()->nullable()->default(0)->index('idx_is_deleted')->comment('是否已删除：0正常，1已删除');
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
        Schema::dropIfExists('sports_basketball_season');
    }
}
