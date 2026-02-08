<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsFootballCompetitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_football_competition', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('category_id')->nullable()->index('idx_category_id')->comment('分类id');
            $table->unsignedInteger('country_id')->nullable()->index('idx_country_id')->comment('国家id');
            $table->string('name_zh')->nullable()->comment('中文名称');
            $table->string('name_zht')->nullable()->comment('粤语名称');
            $table->string('name_en')->nullable()->default('')->comment('英文名称');
            $table->string('short_name_zh')->nullable()->comment('中文简称');
            $table->string('short_name_zht')->nullable()->comment('粤语简称');
            $table->string('short_name_en')->nullable()->default('')->comment('英文简称');
            $table->string('logo')->nullable()->default('')->comment('logo');
            $table->boolean('type')->nullable()->comment('类型：0-未知、1-联赛、2-杯赛、3-友谊赛');
            $table->unsignedInteger('cur_season_id')->nullable()->index('idx_cur_season_id')->comment('当前赛季id');
            $table->unsignedInteger('cur_stage_id')->nullable()->index('idx_cur_stage_id')->comment('当前阶段id');
            $table->unsignedInteger('cur_round')->nullable()->comment('当前轮次');
            $table->unsignedInteger('round_count')->nullable()->comment('总轮次');
            $table->longText('title_holder')->nullable()->comment('卫冕冠军 字段说明（有数据，字段存在）');
            $table->longText('most_titles')->nullable()->comment('夺冠最多球队 字段说明（有数据，字段存在）');
            $table->longText('newcomers')->nullable()->comment('晋级淘汰球队 字段说明（有数据，字段存在）');
            $table->longText('divisions')->nullable()->comment('赛事层级 字段说明（有数据，字段存在）');
            $table->text('host')->nullable()->comment('东道主（有数据，字段存在）');
            $table->string('primary_color')->nullable()->default('')->comment('主颜色，可能不存在');
            $table->string('secondary_color')->nullable()->default('')->comment('次颜色，可能不存在');
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
        Schema::dropIfExists('sports_football_competition');
    }
}
