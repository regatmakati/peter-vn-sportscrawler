<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsDota2MatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_dota2_match', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->integer('box')->nullable()->comment('总局数');
            $table->integer('tournament_id')->nullable()->index('idx_tournament_id')->comment('赛事id');
            $table->integer('stage_id')->nullable()->index('idx_stage_id')->comment('阶段id');
            $table->integer('home_id')->nullable()->index('idx_home_id')->comment('主队id');
            $table->integer('away_id')->nullable()->index('idx_away_id')->comment('客队id');
            $table->integer('status_id')->nullable()->comment('比赛状态，|0:比赛异常，说明：暂未判断具体原因的异常比赛，建议隐藏处理|1:未开赛|2:进行中|3:完场|11:中断|12:取消|13:延期|14:腰斩|15:待定');
            $table->integer('match_time')->nullable()->index('idx_atch_time')->comment('比赛时间');
            $table->string('description')->nullable()->comment('备注说明');
            $table->integer('home_score')->nullable()->comment('主队获胜局数');
            $table->integer('away_score')->nullable()->comment('客队获胜局数');
            $table->string('animations')->nullable()->comment('动画直播地址');
            $table->string('live_url_1')->nullable()->default('')->comment('直播地址');
            $table->string('live_url_2')->nullable()->default('')->comment('直播地址');
            $table->string('live_url_3')->nullable()->default('')->comment('直播地址');
            $table->unsignedInteger('updated_time')->nullable()->comment('纳米更新时间');
            $table->timestamps()->comment('更新时间');
            $table->unsignedTinyInteger('is_deleted')->nullable()->default(0)->index('idx_is_deleted')->comment('是否已删除：0正常，1已删除');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sports_dota2_match');
    }
}
