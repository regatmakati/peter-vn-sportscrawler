<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateCmfSportsFootballPlayerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement("
            CREATE TABLE `cmf_sports_football_player` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `playerId` int(10) unsigned NOT NULL COMMENT '球员ID',
              `teamId` int(10) unsigned NOT NULL COMMENT '球队ID',
              `nameCnShort` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '球员名（中文简体简称）',
              `nameEn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '球员名（英文简体）',
              `nameEnShort` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '球员名（英文简称）',
              `nameTrad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '球员名（中文繁体）',
              `number` smallint(5) DEFAULT NULL COMMENT '球员球衣编号',
              `kind` tinyint(1) unsigned DEFAULT NULL COMMENT '类型 ： 1-主队首发 ，2-客队首发， 3-主队替补， 4-客队替补',
              `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '位置',
              `created_at` datetime DEFAULT NULL COMMENT '创建时间',
              `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
              PRIMARY KEY (`id`),
              UNIQUE KEY `unq_playerId_teamId` (`playerId`,`teamId`) USING BTREE,
              KEY `idx_teamId` (`teamId`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='足球球员表';
	    ");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cmf_sports_football_player');
	}

}
