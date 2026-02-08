<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCmfAvatarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE TABLE `cmf_avatar` (
              `id` int NOT NULL AUTO_INCREMENT,
              `image` varchar(255)  NOT NULL DEFAULT '' COMMENT '用户头像',
              `addtime` int NOT NULL DEFAULT '0' COMMENT '添加时间',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户头像表';

	    ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmf_avatar');
    }
}
