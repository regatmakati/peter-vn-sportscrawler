<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsDota2TournamentPvpLineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_dota2_tournament_pvp_line', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->integer('tournament_id')->nullable()->index('idx_tournament_id')->comment('赛事id');
            $table->integer('stage_id')->nullable()->index('idx_stage_id')->comment('阶段id');
            $table->integer('part_stage_id')->nullable()->index('idx_part_stage_id')->comment('副阶段id');
            $table->text('lines')->nullable()->comment('对阵中比赛的连线，num标识连线');
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
        Schema::dropIfExists('sports_dota2_tournament_pvp_line');
    }
}
