<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballMatchAnalysisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_match_analysis', function (Blueprint $table) {
            $table->unsignedInteger('match_id')->primary();
            $table->longText('info')->nullable()->comment('比赛信息字段说明');
            $table->longText('result')->nullable()->comment('历史交锋/近期战绩');
            $table->longText('fixture')->nullable()->comment('未来赛程');
            $table->longText('teams')->nullable()->comment('球队项，key-球队id');
            $table->longText('events')->nullable()->comment('赛事项，key-赛事id');
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
        Schema::dropIfExists('sports_basketball_match_analysis');
    }
}
