<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsDota2EquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_dota2_equipment', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('name_zh')->default('')->comment('中文名称');
            $table->string('name_en')->default('')->comment('英文名称');
            $table->string('logo')->default('')->comment('logo');
            $table->unsignedInteger('updated_time')->nullable()->comment('纳米更新时间');
            $table->timestamps()->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sports_dota2_equipment');
    }
}
