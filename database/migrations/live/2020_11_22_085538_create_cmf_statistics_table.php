<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateCmfStatisticsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement("
            CREATE TABLE `cmf_statistics` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `type` tinyint(1) unsigned NOT NULL COMMENT '类型：1、app下载',
			  `action` tinyint(1) unsigned NOT NULL COMMENT '操作：1、PC端android，2、PC端ios，3、h5端android，4、h5端ios',
			  `click_cnt` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击量',
			  `date` date NOT NULL COMMENT '日期',
			  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
			  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
			  PRIMARY KEY (`id`) USING BTREE,
			  UNIQUE KEY `unq_type_action_date` (`type`,`action`,`date`) USING BTREE,
			  KEY `idx_date` (`date`) USING BTREE,
			  KEY `idx_action` (`action`) USING BTREE
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='统计表';
	    ");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cmf_statistics');
	}

}
