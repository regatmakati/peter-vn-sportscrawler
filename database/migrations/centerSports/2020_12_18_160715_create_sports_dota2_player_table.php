<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsDota2PlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_dota2_player', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('name_zh')->default('')->comment('中文名称');
            $table->string('name_en')->default('')->comment('英文名称');
            $table->string('abbr_zh')->default('')->comment('中文简称');
            $table->string('abbr_en')->default('')->comment('英文简称');
            $table->string('logo')->default('')->comment('logo');
            $table->unsignedInteger('updated_time')->nullable()->comment('纳米更新时间');
            $table->integer('team_id')->nullable()->index('idx_team_id')->comment('战队id');
            $table->integer('country_id')->nullable()->index('idx_country_id')->comment('国家id');
            $table->string('real_name')->nullable()->comment('真实名称');
            $table->integer('birthday')->nullable()->comment('生日');
            $table->unsignedTinyInteger('retired')->nullable()->comment('是否退役，1.是、0.否');
            $table->unsignedTinyInteger('position')->nullable()->comment('号位（6-教练）');
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
        Schema::dropIfExists('sports_dota2_player');
    }
}
