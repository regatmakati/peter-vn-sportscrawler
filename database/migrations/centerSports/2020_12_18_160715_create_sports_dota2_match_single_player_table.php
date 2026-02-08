<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsDota2MatchSinglePlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_dota2_match_single_player', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->integer('match_single_id')->nullable()->index('idx_match_single_id')->comment('比赛单局id');
            $table->integer('team_id')->nullable()->index('idx_team_id')->comment('战队id');
            $table->integer('player_id')->nullable()->index('idx_player_id')->comment('选手id');
            $table->integer('hero_id')->nullable()->index('idx_hero_id')->comment('英雄id');
            $table->integer('position')->nullable()->comment('号位');
            $table->integer('kill')->nullable()->comment('击杀数');
            $table->integer('die')->nullable()->comment('死亡数');
            $table->integer('assists')->nullable()->comment('助攻数');
            $table->string('equipments')->nullable()->comment('装备ids（0-未知，占位）');
            $table->integer('level')->nullable()->comment('英雄等级');
            $table->integer('last_hits')->nullable()->comment('正补');
            $table->integer('denies')->nullable()->comment('反补');
            $table->integer('gpm')->nullable()->comment('分钟经济');
            $table->integer('xpm')->nullable()->comment('分钟经验');
            $table->integer('hero_damage')->nullable()->comment('对英雄造成的伤害');
            $table->integer('tower_damage')->nullable()->comment('对防御塔造成的伤害');
            $table->integer('gold')->nullable()->comment('剩余金钱');
            $table->integer('gold_spent')->nullable()->comment('花费金钱');
            $table->string('spell_order')->nullable()->comment('技能加点顺序（PS：技能和天赋的加点顺序是一起的）
example：["0,167", "1,168", "2,167", "3,169"]');
            $table->string('rune_order')->nullable()->comment('点亮的天赋树顺序（PS：技能和天赋的加点顺序是一起的）
example：["9,737", "14,340", "17,341"]');
            $table->string('equipments_order')->nullable()->comment('出装顺序
example：["51,14", "145,23", "145,17", "184,78"]');
            $table->unsignedInteger('updated_time')->nullable()->comment('纳米更新时间');
            $table->timestamps()->comment('更新时间');
            $table->unsignedTinyInteger('is_deleted')->nullable()->default(0)->comment('是否已删除：0正常，1已删除');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sports_dota2_match_single_player');
    }
}
