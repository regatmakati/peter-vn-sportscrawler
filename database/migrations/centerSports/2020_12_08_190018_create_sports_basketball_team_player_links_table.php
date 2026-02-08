<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballTeamPlayerLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_team_player_links', function (Blueprint $table) {
            $table->unsignedInteger('team_id')->comment('球队id');
            $table->unsignedInteger('player_id')->index('idx_player_id')->comment('球员id');
            $table->timestamps();
            $table->unique(['team_id', 'player_id'], 'unq_team_id_player_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sports_basketball_team_player_links');
    }
}
