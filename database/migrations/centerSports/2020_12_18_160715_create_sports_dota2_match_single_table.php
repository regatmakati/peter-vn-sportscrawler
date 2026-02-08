<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsDota2MatchSingleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_dota2_match_single', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->integer('match_id')->nullable()->comment('比赛id');
            $table->integer('status_id')->nullable()->index('idx_status_id')->comment('比赛状态，|0:比赛异常，说明：暂未判断具体原因的异常比赛，建议隐藏处理|1:未开赛|2:进行中|3:完场|11:中断|12:取消|13:延期|14:腰斩|15:待定');
            $table->integer('match_time')->nullable()->index('idx_match_time')->comment('比赛时间');
            $table->integer('box_num')->nullable()->index('idx_box_num')->comment('第几局');
            $table->text('radiant')->nullable()->comment('天辉');
            $table->text('dire')->nullable()->comment('夜魇');
            $table->integer('length_time')->comment('比赛时长');
            $table->integer('first_blood')->nullable()->comment('一血的战队id');
            $table->integer('first_tower')->nullable()->comment('一塔的战队id');
            $table->integer('first_roushan')->nullable()->comment('首肉山的战队id');
            $table->integer('five_kill')->nullable()->comment('先五杀的战队id');
            $table->integer('ten_kill')->nullable()->comment('先十杀的战队id');
            $table->string('eco_list', 5000)->nullable()->comment('经济曲线：天辉与夜魇的差值，按分钟数变化');
            $table->string('exp_list', 5000)->nullable()->comment('经验曲线：天辉与夜魇的差值，按分钟数变化');
            $table->integer('roshan_timer')->nullable()->comment('肉山刷新剩余时间-秒');
            $table->string('timer')->nullable()->comment('比赛时间字段说明');
            $table->boolean('is_bp')->nullable()->comment('是否处于bp环节，1.是、0.否
(注：bp环节不属于比赛时间)');
            $table->text('bp_data')->nullable()->comment('bp数据');
            $table->unsignedInteger('updated_time')->nullable()->comment('纳米更新时间');
            $table->timestamps()->comment('更新时间');
            $table->unsignedTinyInteger('is_deleted')->nullable()->default(0)->comment('是否已删除：0正常，1已删除');
            $table->unique(['match_id', 'box_num'], 'unq_match_id_box_num');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sports_dota2_match_single');
    }
}
