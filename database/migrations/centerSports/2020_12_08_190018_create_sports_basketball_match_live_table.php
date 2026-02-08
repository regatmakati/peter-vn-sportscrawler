<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballMatchLiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_match_live', function (Blueprint $table) {
            $table->unsignedInteger('match_id')->primary();
            $table->longText('tlive')->nullable()->comment('文字直播内容');
            $table->longText('score')->nullable()->comment('比分');
            $table->longText('stats')->nullable()->comment('比赛状态');
            $table->longText('players')->nullable()->comment('阵容');
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
        Schema::dropIfExists('sports_basketball_match_live');
    }
}
