<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CmfSportsFootballMatchTable20201105085539 extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("
          ALTER TABLE `cmf_sports_football_match` 
          MODIFY COLUMN `state` smallint(6) DEFAULT 0 COMMENT '比赛状态:-14:推迟，-13:中断，-12:腰斩，-11:待定，-10:取消，-1.完场，0:未开始，1:上半场，2:中场，3:下半场，4:加时，5:点球 ' AFTER `matchStartTime`;
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
