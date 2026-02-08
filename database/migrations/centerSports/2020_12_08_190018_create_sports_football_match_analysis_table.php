<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsFootballMatchAnalysisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_football_match_analysis', function (Blueprint $table) {
            $table->unsignedInteger('match_id')->primary();
            $table->longText('goal_distribution')->nullable()->comment('进球分布，可能不存在');
            $table->longText('history')->nullable()->comment('历史交锋/近期战绩');
            $table->longText('info')->nullable()->comment('比赛信息字段说明');
            $table->longText('injury')->nullable()->comment('伤停情况');
            $table->longText('matchevents')->nullable()->comment('赛事项，key-赛事id');
            $table->longText('teams')->nullable()->comment('球队');
            $table->longText('table')->nullable()->comment('联赛积分，可能不存在');
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
        Schema::dropIfExists('sports_football_match_analysis');
    }
}
