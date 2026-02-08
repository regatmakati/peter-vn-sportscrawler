<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_country', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('category_id')->index('idx_category_id')->comment('分类id');
            $table->string('name_zh')->comment('中文名称');
            $table->string('name_zht')->comment('粤语名称');
            $table->string('name_en')->default('')->comment('英文名称');
            $table->string('logo')->default('')->comment('国家logo');
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
        Schema::dropIfExists('sports_basketball_country');
    }
}
