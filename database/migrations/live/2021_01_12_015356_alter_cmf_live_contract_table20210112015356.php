<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterCmfLiveContractTable20210112015356 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            ALTER TABLE `live`.`cmf_live_contract`
            ADD COLUMN `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '显示状态：0（默认）隐藏全部，1显示全部 ，2显示按钮' AFTER `type`;
	    ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
