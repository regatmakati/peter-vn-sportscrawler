<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballStageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_stage', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('season_id')->index('idx_season_id')->comment('赛季id');
            $table->boolean('mode')->unsigned()->index('idx_mode')->comment('比赛模式，1-积分赛、2-淘汰赛、3-资格赛');
            $table->unsignedSmallInteger('group_count')->comment('总组数');
            $table->unsignedSmallInteger('round_count')->comment('总轮数');
            $table->unsignedSmallInteger('order')->index('idx_order')->comment('排序，阶段的先后顺序');
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
        Schema::dropIfExists('sports_basketball_stage');
    }
}
