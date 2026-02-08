<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsFootballCompensationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_football_compensation', function (Blueprint $table) {
            $table->unsignedInteger('match_id')->primary()->comment('比赛id');
            $table->longText('history')->nullable()->comment('历史交锋');
            $table->longText('recent')->nullable()->comment('近期战绩');
            $table->longText('similar')->nullable()->comment('历史同赔');
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
        Schema::dropIfExists('sports_football_compensation');
    }
}
