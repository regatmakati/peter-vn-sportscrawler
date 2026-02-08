<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsDota2TournamentTeamLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_dota2_tournament_team_links', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->integer('tournament_id')->nullable()->comment('赛事id');
            $table->integer('team_id')->nullable()->index('idx_team_id')->comment('队伍id');
            $table->timestamps()->comment('更新时间');
            $table->unique(['tournament_id', 'team_id'], 'unq_tournament_id_team_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sports_dota2_tournament_team_links');
    }
}
