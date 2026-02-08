<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsFootballMatchLineupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_football_match_lineup', function (Blueprint $table) {
            $table->unsignedInteger('match_id')->primary()->comment('比赛id');
            $table->unsignedInteger('home_team_id')->index('idx_home_team_id')->comment('主队id');
            $table->longText('home')->nullable()->comment('主队阵型球员列表');
            $table->string('home_formation')->nullable()->comment('主队阵型');
            $table->unsignedInteger('away_team_id')->index('idx_away_team_id')->comment('客队id');
            $table->longText('away')->nullable()->comment('客队阵型球员列表');
            $table->string('away_formation')->nullable()->comment('客队阵型');
            $table->boolean('confirmed')->nullable()->comment('正式阵容，1-是、0-不是');
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
        Schema::dropIfExists('sports_football_match_lineup');
    }
}
