<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsDota2TournamentRankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_dota2_tournament_rank', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->integer('team_id')->nullable()->index('idx_team_id')->comment('战队id');
            $table->integer('stage_id')->nullable()->index('idx_stage_id')->comment('阶段id');
            $table->integer('part_stage_id')->nullable()->index('idx_part_stage_id')->comment('副阶段id');
            $table->integer('tournament_id')->nullable()->index('idx_tournament_id')->comment('赛事id');
            $table->integer('win')->nullable()->comment('胜');
            $table->integer('lose')->nullable()->comment('负');
            $table->integer('equal')->nullable()->comment('平');
            $table->integer('score')->nullable()->comment('积分');
            $table->unsignedInteger('updated_time')->nullable()->comment('纳米更新时间');
            $table->timestamps()->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sports_dota2_tournament_rank');
    }
}
