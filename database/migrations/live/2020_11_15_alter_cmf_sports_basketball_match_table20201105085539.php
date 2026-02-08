<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CmfSportsBasketballMatchTable20201105085539 extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("
          ALTER TABLE `cmf_sports_basketball_match` 
          MODIFY COLUMN `state` smallint(6) NOT NULL DEFAULT 0 COMMENT '比赛状态:  0：比赛一场，1：未开赛，2：第一节，3：第一节完，4：第二节，5：第二节完，6：第三节，7：第三节完，8：第四节，9：加时，10：完场，11：中断，12：取消，13：延期，14：腰斩，15：待定' AFTER `awayRank`;
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
