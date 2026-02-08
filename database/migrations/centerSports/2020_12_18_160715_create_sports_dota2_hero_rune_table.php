<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsDota2HeroRuneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_dota2_hero_rune', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('name_zh')->default('')->comment('中文名称');
            $table->string('name_en')->default('')->comment('英文名称');
            $table->string('abbr_zh')->default('')->comment('中文简称');
            $table->string('abbr_en')->default('')->comment('英文简称');
            $table->string('logo')->default('')->comment('logo');
            $table->string('icon')->nullable()->comment('小图');
            $table->string('vert_logo')->nullable()->comment('竖图');
            $table->string('attrs')->nullable()->comment('[0:"核心,1:"辅助,2:"耐久,3:"控制,4:"先手,5:"爆发,6:"逃生,7:"打野,8:"推进]');
            $table->unsignedInteger('num')->nullable()->comment('天赋树的顺序，10级(1、2)、15级(3、4)、20级(5、6)、25级(7、8)');
            $table->unsignedInteger('hero_id')->nullable()->comment('英雄id');
            $table->string('description')->nullable()->comment('天赋描述');
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
        Schema::dropIfExists('sports_dota2_hero_rune');
    }
}
