<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballCompetitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_competition', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('category_id')->nullable()->comment('分类id');
            $table->unsignedInteger('country_id')->nullable()->comment('国家id');
            $table->string('name_zh')->nullable()->comment('中文名称');
            $table->string('name_zht')->nullable()->comment('粤语名称');
            $table->string('name_en')->nullable()->default('')->comment('英文名称');
            $table->string('short_name_zh')->nullable()->comment('中文简称');
            $table->string('short_name_zht')->nullable()->comment('粤语简称');
            $table->string('short_name_en')->nullable()->default('')->comment('英文简称');
            $table->string('logo')->nullable()->default('')->comment('logo');
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
        Schema::dropIfExists('sports_basketball_competition');
    }
}
