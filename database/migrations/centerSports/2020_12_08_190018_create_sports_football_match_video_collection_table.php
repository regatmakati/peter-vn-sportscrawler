<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsFootballMatchVideoCollectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_football_match_video_collection', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('match_id')->index('idx_match_id')->comment('比赛id');
            $table->string('title')->nullable()->comment('名称');
            $table->string('pc_link')->nullable()->default('')->comment('web直播地址');
            $table->string('mobile_link')->nullable()->default('')->comment('wap直播地址');
            $table->string('cover')->nullable()->default('')->comment('图片');
            $table->unsignedMediumInteger('duration')->nullable()->comment('时长-秒（s）');
            $table->boolean('type')->unsigned()->index('idx_type')->comment('类型，1-集锦、2-录像');
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
        Schema::dropIfExists('sports_football_match_video_collection');
    }
}
