<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballInjuryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_injury', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('player_id')->nullable()->index('idx_player_id')->comment('球员id');
            $table->unsignedInteger('team_id')->nullable()->index('idx_team_id')->comment('球队id');
            $table->unsignedInteger('competition_id')->nullable()->index('idx_competition_id')->comment('赛事id');
            $table->boolean('type')->unsigned()->nullable()->index('idx_type')->comment('类型，1.受伤、2.停赛、3.缺席');
            $table->string('reason')->nullable()->default('')->comment('受伤原因');
            $table->unsignedInteger('start_time')->nullable()->comment('开始时间');
            $table->unsignedInteger('end_time')->nullable()->comment('结束时间');
            $table->boolean('status')->nullable()->comment('状态，1.无法出场、2.赛季报销、3.每日观察');
            $table->unsignedSmallInteger('missed_matches')->nullable()->comment('缺失比赛场次');
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
        Schema::dropIfExists('sports_basketball_injury');
    }
}
