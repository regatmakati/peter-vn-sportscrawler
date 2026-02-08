<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsDota2TournamentPvpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_dota2_tournament_pvp', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->integer('match_id')->nullable()->index('idx_match_id')->comment('比赛id');
            $table->integer('stage_id')->nullable()->index('idx_stage_id')->comment('阶段id');
            $table->integer('part_stage_id')->nullable()->index('idx_part_stage_id')->comment('副阶段id');
            $table->integer('round_id')->nullable()->index('idx_round_id')->comment('轮次id');
            $table->integer('column_num')->nullable()->comment('第几列');
            $table->integer('row_num')->nullable()->comment('第几个，同一列下，多场比赛的排序');
            $table->integer('sequence_type')->nullable()->comment('层级，用于关联同一组轮次（如1同属于胜者组，2同属于败者组）');
            $table->integer('num')->nullable()->comment('num标识，用于连线');
            $table->integer('tournament_id')->nullable()->index('idx_tournament_id')->comment('赛事id');
            $table->boolean('is_promotion')->nullable()->index('idx_is_promotion')->comment('是否晋级，1.是、0.否');
            $table->string('promotion_name')->nullable()->comment('晋级名称');
            $table->unsignedInteger('updated_time')->nullable()->comment('纳米更新时间');
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
        Schema::dropIfExists('sports_dota2_tournament_pvp');
    }
}
