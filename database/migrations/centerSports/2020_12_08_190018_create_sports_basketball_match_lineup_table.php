<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballMatchLineupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_match_lineup', function (Blueprint $table) {
            $table->unsignedInteger('match_id')->comment('比赛id');
            $table->unsignedInteger('team_id')->index('idx_team_id')->comment('队伍id');
            $table->unsignedInteger('player_id')->index('idx_player_id')->comment('球员id');
            $table->string('position')->nullable()->comment('位置');
            $table->string('shirt_number')->nullable()->comment('球衣号');
            $table->unsignedInteger('updated_time')->nullable()->comment('纳米更新时间');
            $table->timestamps();
            $table->unique(['match_id', 'team_id', 'player_id'], 'unq_match_id_team_id_player_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sports_basketball_match_lineup');
    }
}
