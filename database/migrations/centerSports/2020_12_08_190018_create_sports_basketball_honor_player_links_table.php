<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballHonorPlayerLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_honor_player_links', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('honor_id')->comment('荣誉id');
            $table->unsignedInteger('player_id')->index('idx_player_id')->comment('球员id');
            $table->unsignedInteger('team_id')->nullable()->comment('球队id');
            $table->unsignedInteger('competition_id')->nullable()->comment('赛事id');
            $table->string('season')->default('')->comment('赛季');
            $table->unsignedInteger('updated_time')->nullable()->comment('纳米更新时间');
            $table->timestamps();
            $table->unique(['honor_id', 'player_id', 'team_id', 'competition_id', 'season'], 'unq_honors_id_player_id_team_id_competition_id_season');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sports_basketball_honor_player_links');
    }
}
