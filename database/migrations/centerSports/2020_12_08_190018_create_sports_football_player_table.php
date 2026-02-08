<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsFootballPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_football_player', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('team_id')->nullable()->index('idx_team_id')->comment('球队id，当球员退役、自由球员、球队未知时，team_id可能为0');
            $table->unsignedInteger('country_id')->nullable()->index('idx_country_id')->comment('分类id');
            $table->string('name_zh')->nullable()->comment('中文名称');
            $table->string('name_en')->nullable()->default('')->comment('英文名称');
            $table->string('short_name_zh')->nullable()->comment('中文简称');
            $table->string('short_name_en')->nullable()->default('')->comment('英文简称');
            $table->integer('birthday')->nullable()->comment('生日');
            $table->unsignedTinyInteger('age')->nullable()->comment('年龄');
            $table->unsignedSmallInteger('weight')->nullable()->comment('体重');
            $table->unsignedSmallInteger('height')->nullable()->comment('身高');
            $table->string('nationality')->nullable()->comment('国籍');
            $table->unsignedInteger('market_value')->nullable()->comment('市值');
            $table->string('market_value_currency')->nullable()->comment('市值单位');
            $table->unsignedInteger('contract_until')->nullable()->comment('合同截止时间');
            $table->string('position')->nullable()->comment('擅长位置，F-前锋、M-中场、D-后卫、G-守门员、其他为未知');
            $table->string('positions', 500)->nullable()->comment('["主要位置 - string", ["次要位置列表 - string"]]');
            $table->unsignedTinyInteger('preferred_foot')->nullable()->comment('惯用脚，0-未知、1-左脚、2-右脚、3-左右脚');
            $table->string('characteristics', 1000)->nullable()->comment('技术特点字段说明');
            $table->string('ability', 1000)->nullable()->comment('能力评分字段说明');
            $table->boolean('is_deleted')->unsigned()->nullable()->default(0)->index('idx_is_deleted')->comment('是否已删除：0正常，1已删除');
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
        Schema::dropIfExists('sports_football_player');
    }
}
