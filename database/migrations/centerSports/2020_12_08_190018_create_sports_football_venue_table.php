<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsFootballVenueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_football_venue', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('country_id')->index('idx_country_id')->comment('国家id');
            $table->string('name_zh')->comment('中文名称');
            $table->string('name_en')->default('')->comment('英文名称');
            $table->string('logo')->default('')->comment('logo');
            $table->string('city')->default('')->comment('城市');
            $table->string('capacity')->default('')->comment('球场容量');
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
        Schema::dropIfExists('sports_football_venue');
    }
}
