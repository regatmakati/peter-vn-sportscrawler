<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsFootballHonorTeamLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_football_honor_team_links', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('team_id')->index('idx_team_id')->comment('队伍id');
            $table->unsignedInteger('honors_id')->comment('荣誉id');
            $table->string('season')->default('')->comment('赛季');
            $table->unsignedInteger('updated_time')->nullable()->comment('纳米更新时间');
            $table->timestamps();
            $table->unique(['honors_id', 'team_id'], 'unq_honors_id_team_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sports_football_honor_team_links');
    }
}
