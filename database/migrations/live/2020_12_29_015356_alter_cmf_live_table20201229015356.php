<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterCmfLiveTable20201229015356 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            ALTER TABLE `cmf_live`
            ADD COLUMN `recordid` varchar(255) DEFAULT '' COMMENT '录播任务ID' AFTER `isrecord`;
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
