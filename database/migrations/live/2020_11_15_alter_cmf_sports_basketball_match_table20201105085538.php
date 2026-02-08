<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CmfSportsBasketballMatchTable20201105085538 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement("
            ALTER TABLE `cmf_sports_basketball_match` 
            ADD COLUMN `live_url` varchar(500) DEFAULT '' COMMENT '直播地址' AFTER `match_date`;
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
