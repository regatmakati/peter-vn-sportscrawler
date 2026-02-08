<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsFootballCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_football_category', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('name_zh')->unique('unq_teamId')->comment('中文名称');
            $table->string('name_zht')->comment('粤语名称');
            $table->string('name_en')->default('')->comment('英文名称');
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
        Schema::dropIfExists('sports_football_category');
    }
}
