<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateCmfSportsFootballOddsFlowListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement("
            CREATE TABLE `cmf_sports_football_odds_flow_list` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `matchId` int(10) unsigned NOT NULL COMMENT '比赛ID',
              `companyId` int(10) unsigned NOT NULL COMMENT '指数公司ID',
              `companyName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '公司名称',
              `oddsId` int(10) unsigned NOT NULL COMMENT '指数流水ID',
              `leftOdds` double(5,2) NOT NULL COMMENT '主队胜赔率（亚盘/欧赔）,大球胜赔率（大小球）',
              `middleOdds` double(5,2) NOT NULL COMMENT '盘口（亚盘），平局赔率（欧赔）',
              `rightOdds` double(5,2) NOT NULL COMMENT '客队胜赔率（亚盘/欧赔）,小球胜赔率（大小球）',
              `changeTime` int(10) unsigned NOT NULL COMMENT '变盘时间，单位：秒',
              `isEntertained` tinyint(1) unsigned NOT NULL COMMENT '是否封盘 1：未封盘，2：临时性封盘或停止走地  ',
              `homeScore` smallint(5) unsigned NOT NULL COMMENT '当前时间的主队比分',
              `awayScore` smallint(5) unsigned NOT NULL COMMENT '当前时间的客队比分',
              `type` tinyint(1) unsigned NOT NULL COMMENT '类型，1：无类型数据 2：早餐盘 3：即时盘, 4：走地盘',
              `from` tinyint(1) unsigned NOT NULL COMMENT '类型：1亚盘，2大小球，3欧赔，4百家欧赔',
              `created_at` datetime DEFAULT NULL COMMENT '创建时间',
              `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
              PRIMARY KEY (`id`),
              UNIQUE KEY `unq_matchId` (`matchId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='足球比赛公司指数流水表';
	    ");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cmf_sports_football_odds_flow_list');
	}

}
