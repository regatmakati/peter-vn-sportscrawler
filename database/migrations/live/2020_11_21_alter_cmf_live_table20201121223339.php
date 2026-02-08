<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CmfLiveTable20201121223339 extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("
          ALTER TABLE `cmf_live`
ADD COLUMN `third_pull`  varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''  DEFAULT '' COMMENT '第三方播流地址' AFTER `pull`;
	    ");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	}

}
