<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsDota2TournamentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_dota2_tournament', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->integer('status_id')->nullable()->index('idx_status_id')->comment('状态，详见状态码->比赛状态');
            $table->string('name_zh')->default('')->comment('中文名称');
            $table->string('name_en')->default('')->comment('英文名称');
            $table->string('abbr_zh')->default('')->comment('中文简称');
            $table->string('abbr_en')->default('')->comment('英文简称');
            $table->string('logo')->default('')->comment('logo');
            $table->string('cover')->nullable()->comment('封面');
            $table->integer('start_time')->nullable()->comment('开始时间');
            $table->integer('end_time')->nullable()->comment('结束时间');
            $table->string('city_name')->nullable()->comment('举办地 中文名称');
            $table->string('city_name_en')->nullable()->comment('举办地 英文名称');
            $table->string('price_pool')->nullable()->comment('奖金池');
            $table->unsignedTinyInteger('type')->nullable()->index('idx_type')->comment('赛事类型，1.国际邀请赛、2.甲级联赛、3.乙级联赛、4.预选赛、5.其他赛事、0.未知');
            $table->unsignedInteger('updated_time')->nullable()->comment('纳米更新时间');
            $table->timestamps()->comment('更新时间');
            $table->unsignedTinyInteger('is_deleted')->nullable()->default(0)->comment('是否已删除：0正常，1已删除');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sports_dota2_tournament');
    }
}
