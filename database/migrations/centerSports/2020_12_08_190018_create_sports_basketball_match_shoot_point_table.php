<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballMatchShootPointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_match_shoot_point', function (Blueprint $table) {
            $table->unsignedInteger('match_id')->index('idx_match_id')->comment('分类id');
            $table->unsignedInteger('team_id')->nullable()->index('idx_team_id')->comment('球队id');
            $table->unsignedInteger('shoot_player_id')->nullable()->index('idx_shoot_player_id')->comment('投篮球员id');
            $table->unsignedInteger('assist_player_id')->nullable()->index('idx_assist_player_id')->comment('助攻球员id - int');
            $table->unsignedTinyInteger('is_hit')->nullable()->comment('是否命中，1.是、0.否 - int');
            $table->unsignedTinyInteger('x')->nullable()->comment('x坐标');
            $table->unsignedTinyInteger('y')->nullable()->comment('y坐标');
            $table->unsignedTinyInteger('count')->nullable()->comment('小节数');
            $table->string('count_time')->nullable()->default('')->comment('小节剩余时间');
            $table->boolean('socore')->unsigned()->nullable()->comment('得分');
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
        Schema::dropIfExists('sports_basketball_match_shoot_point');
    }
}
