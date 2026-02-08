<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsDota2MatchLiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_dota2_match_live', function (Blueprint $table) {
            $table->unsignedInteger('match_id')->primary()->comment('比赛id');
            $table->integer('tournament_id')->nullable()->index('idx_tournament_id')->comment('赛事id');
            $table->integer('roshan_timer')->nullable()->comment('肉山刷新剩余时间-秒');
            $table->string('timer')->nullable()->comment('比赛时间字段说明');
            $table->text('home')->nullable()->comment('主队');
            $table->text('away')->nullable()->comment('客队');
            $table->string('economy_lines', 2000)->nullable()->comment('经济曲线，主队与客队的差值');
            $table->string('experience_lines', 2000)->nullable()->comment('经验曲线，主队与客队的差值');
            $table->boolean('is_bp')->nullable()->index('idx_is_bp')->comment('是否处于bp环节，1.是、0.否
(注：bp环节不属于比赛时间)');
            $table->text('bp_data')->nullable()->comment('bp数据');
            $table->timestamps()->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sports_dota2_match_live');
    }
}
