<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterCmfLiveRecord20201224184842 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            ALTER TABLE `cmf_live_record`
            ADD COLUMN `pcnums` int(0) DEFAULT 0 COMMENT '关播时PC人数' AFTER `nums`,
            ADD COLUMN `androidnums` int(0) DEFAULT 0 COMMENT '关播时安卓人数' AFTER `nums`,
            ADD COLUMN `iosnums` int(0) DEFAULT 0 COMMENT '关播时IOS人数' AFTER `nums`,
            ADD COLUMN `h5nums` int(0) DEFAULT 0 COMMENT '关播时H5人数' AFTER `nums`;
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
