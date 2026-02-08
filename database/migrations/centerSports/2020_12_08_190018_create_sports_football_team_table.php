<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsFootballTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_football_team', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('competition_id')->nullable()->index('idx_competition_id')->comment('赛事id');
            $table->unsignedInteger('country_id')->nullable()->index('idx_country_id')->comment('分类id');
            $table->string('name_zh')->nullable()->comment('中文名称');
            $table->string('name_zht')->nullable()->comment('粤语名称');
            $table->string('name_en')->nullable()->default('')->comment('英文名称');
            $table->string('short_name_zh')->nullable()->comment('中文简称');
            $table->string('short_name_zht')->nullable()->comment('粤语简称');
            $table->string('short_name_en')->nullable()->default('')->comment('英文简称');
            $table->string('logo')->nullable()->default('')->comment('国家logo');
            $table->boolean('national')->unsigned()->nullable()->comment('是否国家队，1-是、0-否');
            $table->unsignedSmallInteger('foundation_time')->nullable()->comment('成立时间');
            $table->string('website')->nullable()->default('')->comment('球队官网');
            $table->unsignedInteger('manager_id')->nullable()->comment('教练id');
            $table->unsignedInteger('venue_id')->nullable()->comment('场馆id');
            $table->unsignedInteger('market_value')->nullable()->comment('市值');
            $table->string('market_value_currency')->nullable()->comment('市值单位');
            $table->string('country_logo')->nullable()->comment('国家队logo');
            $table->mediumInteger('total_players')->nullable()->comment('总球员数，-1表示没有该字段数据');
            $table->mediumInteger('foreign_players')->nullable()->comment('非本土球员数，-1表示没有该字段数据');
            $table->mediumInteger('national_players')->nullable()->comment('国家队球员数，-1表示没有该字段数据');
            $table->boolean('is_deleted')->unsigned()->nullable()->default(0)->index('idx_is_deleted')->comment('是否已删除：0正常，1已删除');
            $table->unsignedMediumInteger('points')->nullable()->comment('积分');
            $table->unsignedMediumInteger('ranking')->nullable()->comment('排名');
            $table->unsignedMediumInteger('position_changed')->nullable()->comment('排名变化');
            $table->unsignedMediumInteger('previous_points')->nullable()->comment('上次积分');
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
        Schema::dropIfExists('sports_football_team');
    }
}
