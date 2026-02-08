<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballRefereeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_referee', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('name')->nullable()->comment('名称');
            $table->string('short_name')->nullable()->default('')->comment('简称');
            $table->unsignedInteger('birthday')->nullable()->comment('生日');
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
        Schema::dropIfExists('sports_basketball_referee');
    }
}
