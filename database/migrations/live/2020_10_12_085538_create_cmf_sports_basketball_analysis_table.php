<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateCmfSportsBasketballAnalysisTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement("
            CREATE TABLE `cmf_sports_basketball_analysis` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `matchId` int(10) unsigned NOT NULL COMMENT '比赛ID',
              `commonAnalysis` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '近10场的对往战绩列表',
              `homeAnalysis` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '主队近10场的战绩列表',
              `awayAnalysis` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '客队近10场的战绩列表',
              `created_at` datetime DEFAULT NULL COMMENT '创建时间',
              `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
              PRIMARY KEY (`id`),
              UNIQUE KEY `unq_matchId` (`matchId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='篮球比赛分析表';
	    ");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cmf_sports_basketball_analysis');
	}

}
