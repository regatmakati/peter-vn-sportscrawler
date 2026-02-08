<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateCmfSportsFootballTextLiveTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement("
            CREATE TABLE `cmf_sports_football_text_live` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `matchId` int(10) unsigned NOT NULL COMMENT '比赛ID',
              `live` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '直播信息',
              `created_at` datetime DEFAULT NULL COMMENT '创建时间',
              `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='足球文字直播表';
	    ");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cmf_sports_football_live');
	}

}
