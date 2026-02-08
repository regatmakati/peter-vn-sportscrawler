<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateCmfSportsBasketballLeagueTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement("
            CREATE TABLE `cmf_sports_basketball_league` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `leagueId` int(10) unsigned NOT NULL COMMENT '联赛/杯赛ID    ',
              `leagueNameCnShort` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '联赛/杯赛名（中文全称简称）',
              `leagueNameTradShort` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '联赛/杯赛名（繁体全称简称）',
              `leagueNameEnShort` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '联赛/杯赛名（英文全称简称）',
              `created_at` datetime DEFAULT NULL COMMENT '创建时间',
              `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
              PRIMARY KEY (`id`),
              UNIQUE KEY `unq_leagueId` (`leagueId`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='篮球联赛/杯赛表';
	    ");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cmf_sports_basketball_league');
	}

}
