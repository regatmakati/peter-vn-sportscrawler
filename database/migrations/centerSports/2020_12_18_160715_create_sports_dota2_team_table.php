<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsDota2TeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_dota2_team', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('name_zh')->default('')->comment('中文名称');
            $table->string('name_en')->default('')->comment('英文名称');
            $table->string('abbr_zh')->default('')->comment('中文简称');
            $table->string('abbr_en')->default('')->comment('英文简称');
            $table->string('logo')->default('')->comment('logo');
            $table->integer('country_id')->nullable()->index('idx_country_id')->comment('国家id');
            $table->integer('region_id')->nullable()->index('idx_region_id')->comment('赛区id');
            $table->integer('create_time')->nullable()->comment('成立时间');
            $table->string('total_earnings')->nullable()->comment('总奖金');
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
        Schema::dropIfExists('sports_dota2_team');
    }
}
