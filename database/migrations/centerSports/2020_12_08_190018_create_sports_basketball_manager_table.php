<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_manager', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('team_id')->nullable()->index('idx_team_id')->comment('分类id');
            $table->string('name_zh')->nullable()->comment('中文名称');
            $table->string('name_en')->nullable()->default('')->comment('英文名称');
            $table->string('logo')->nullable()->default('')->comment('国家logo');
            $table->unsignedInteger('birthday')->nullable()->comment('生日');
            $table->unsignedTinyInteger('age')->nullable()->comment('年龄');
            $table->string('nationality')->nullable()->comment('国籍');
            $table->string('preferred_formation')->nullable()->comment('习惯的阵型');
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
        Schema::dropIfExists('sports_basketball_manager');
    }
}
