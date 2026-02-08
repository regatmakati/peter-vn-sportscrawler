<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateCmfSportsBasketballPlayerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement("
            CREATE TABLE `cmf_sports_basketball_player` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `playerId` int(10) unsigned NOT NULL COMMENT '球员ID',
              `teamId` int(10) unsigned NOT NULL COMMENT '球队ID',
              `playerNameCn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '球员名（中文简体）',
              `playerNameCnShort` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '球员名（中文简体简称）',
              `playerNameEn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '球员名（英文）',
              `playerNameEnShort` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '球员名（英文简称）',
              `playerTrad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '球员名（中文繁体）',
              `playerTradShort` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '球员名（中文繁体简称）',
              `playerLogo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '球员logo',
              `playerNumber` smallint(5) unsigned DEFAULT NULL COMMENT '球员球衣编号',
              `created_at` datetime DEFAULT NULL COMMENT '创建时间',
              `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
              PRIMARY KEY (`id`),
              UNIQUE KEY `unq_playerId` (`playerId`),
              KEY `idx_teamId` (`teamId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='篮球球员表';
	    ");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cmf_sports_basketball_player');
	}

}
