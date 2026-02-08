<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateCmfSportsFootballLineUpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement("
            CREATE TABLE `cmf_sports_football_line_up` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `matchId` int(10) unsigned NOT NULL COMMENT '比赛ID',
              `homeTeamLineUp` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '主队首发阵容',
              `awayTeamLineUp` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '客队首发阵容（同主队首发阵容）',
              `homeTeamSubLineUp` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '主队替补阵容（同主队首发阵容）',
              `awayTeamSubLineUp` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '客队替补阵容（同主队首发阵容）',
              `homeTeamFormation` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '主队阵形',
              `awayTeamFormation` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '客队阵形',
              `homeTeamInjuryList` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '主队伤停阵容',
              `awayTeamInjuryList` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '客队伤停阵容（同主队伤停阵容）',
              `created_at` datetime DEFAULT NULL COMMENT '创建时间',
              `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='足球比赛阵容表';
	    ");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cmf_sports_football_line_up');
	}

}
