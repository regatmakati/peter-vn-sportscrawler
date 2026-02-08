<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_team', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('competition_id')->nullable()->index('idx_competition_id')->comment('赛事id');
            $table->boolean('conference_id')->unsigned()->nullable()->index('idx_conference_id')->comment('赛区id，1-大西洋赛区、2-中部赛区、3-东南赛区、4-太平洋赛区、5-西北赛区、6-西南赛区、7-A组赛区、8-B组赛区、9-C组赛区、10-D组赛区（1~6:NBA 7~10:CBA）、0-无');
            $table->string('name_zh')->nullable()->comment('中文名称');
            $table->string('name_zht')->nullable()->comment('粤语名称');
            $table->string('name_en')->nullable()->default('')->comment('英文名称');
            $table->string('short_name_zh')->nullable()->comment('中文简称');
            $table->string('short_name_zht')->nullable()->comment('粤语简称');
            $table->string('short_name_en')->nullable()->default('')->comment('英文简称');
            $table->string('logo')->nullable()->default('')->comment('国家logo');
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
        Schema::dropIfExists('sports_basketball_team');
    }
}
