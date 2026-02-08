<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_player', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('team_id')->nullable()->index('idx_team_id')->comment('球队id，当球员退役、自由球员、球队未知时，team_id可能为0');
            $table->string('name_zh')->nullable()->comment('中文名称');
            $table->string('name_en')->nullable()->default('')->comment('英文名称');
            $table->string('short_name_zh')->nullable()->comment('中文简称');
            $table->string('short_name_en')->nullable()->default('')->comment('英文简称');
            $table->integer('birthday')->nullable()->comment('生日');
            $table->unsignedTinyInteger('age')->nullable()->comment('年龄');
            $table->unsignedSmallInteger('weight')->nullable()->comment('体重');
            $table->unsignedSmallInteger('height')->nullable()->comment('身高');
            $table->unsignedInteger('shirt_number')->nullable()->comment('合同截止时间');
            $table->string('position')->nullable()->comment('位置，C-中锋、SF-小前锋、PF-大前锋、SG-得分后卫、PG-组织后卫、F-前锋、G-后卫，其它都为未知');
            $table->string('drafted')->nullable();
            $table->string('league_career_age')->nullable();
            $table->string('school')->nullable();
            $table->string('city')->nullable();
            $table->string('salary')->nullable();
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
        Schema::dropIfExists('sports_basketball_player');
    }
}
