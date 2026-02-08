<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterCmfUserTable20210323015356 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            ALTER TABLE `live`.`cmf_user`
            ADD COLUMN `km_uid` int(0) UNSIGNED DEFAULT NULL COMMENT '酷咪用户id' AFTER `id`,
            MODIFY COLUMN `user_type` tinyint unsigned NOT NULL COMMENT '用户类型：1admin，2会员，3酷咪游客，4酷咪用户' AFTER `km_uid`,
            RENAME INDEX `user_login` TO `idx_user_login`,
            RENAME INDEX `user_nicename` TO `idx_user_nicename`,
            ADD UNIQUE INDEX `unq_km_uid`(`km_uid`);
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
