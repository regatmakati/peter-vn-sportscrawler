<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterCmfLiveTable20201228145509 extends Migration
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
            ADD COLUMN `isrecord` tinyint(1) DEFAULT 0 COMMENT '是否录播' AFTER `pic_full_url`;
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
