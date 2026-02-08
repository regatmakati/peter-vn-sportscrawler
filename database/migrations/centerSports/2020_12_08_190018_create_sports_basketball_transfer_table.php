<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_transfer', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('player_id')->nullable()->index('idx_player_id')->comment('球员id');
            $table->unsignedInteger('from_team_id')->nullable()->index('idx_from_team_id')->comment('转出球队id');
            $table->unsignedInteger('to_team_id')->nullable()->index('idx_to_team_id')->comment('转入id');
            $table->boolean('transfer_type')->unsigned()->nullable()->index('idx_transfer_type')->comment('转会类型，1-租借、2-租借结束、3-转会、4-退役、5-选秀、6-已解约、7-已签约、8-未知');
            $table->unsignedInteger('transfer_time')->nullable()->comment('转会时间');
            $table->unsignedInteger('transfer_fee')->nullable()->comment('转会费用');
            $table->string('transfer_desc')->nullable()->default('')->comment('转会描述');
            $table->unsignedInteger('updated_time')->nullable()->comment('纳米更新时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sports_basketball_transfer');
    }
}
