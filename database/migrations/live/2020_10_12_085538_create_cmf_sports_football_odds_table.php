<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateCmfSportsFootballOddsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement("
            CREATE TABLE `cmf_sports_football_odds` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `matchId` int(10) unsigned NOT NULL COMMENT '比赛ID',
              `teamHtAnalysis` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '亚盘指数数据列表',
              `bsOddsList` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '大小球指数数据列表',
              `europeOddsList` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '欧赔指数数据列表 ',
              `europeMoreOddsList` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '百家欧赔指数数据列表',
              `created_at` datetime DEFAULT NULL COMMENT '创建时间',
              `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
              PRIMARY KEY (`id`),
              UNIQUE KEY `unq_matchId` (`matchId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='足球比赛指数表';
	    ");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cmf_sports_football_odds');
	}

}
