<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballMatchPlayerStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_match_player_stats', function (Blueprint $table) {
            $table->unsignedInteger('match_id')->comment('比赛id');
            $table->unsignedInteger('team_id')->index('idx_team_id')->comment('队伍id');
            $table->unsignedInteger('player_id')->index('idx_player_id')->comment('球员id');
            $table->boolean('first')->unsigned()->nullable()->comment('是否首发');
            $table->unsignedSmallInteger('goals')->nullable()->comment('进球');
            $table->unsignedSmallInteger('penalty')->nullable()->comment('点球');
            $table->unsignedSmallInteger('assists')->nullable()->comment('助攻');
            $table->unsignedSmallInteger('minutes_played')->nullable()->comment('出场时间(分钟)');
            $table->unsignedSmallInteger('red_cards')->nullable()->comment('红牌');
            $table->unsignedSmallInteger('yellow_cards')->nullable()->comment('黄牌');
            $table->unsignedSmallInteger('shots')->nullable()->comment('射门');
            $table->unsignedSmallInteger('shots_on_target')->nullable()->comment('射正');
            $table->unsignedSmallInteger('dribble')->nullable()->comment('过人');
            $table->unsignedSmallInteger('dribble_succ')->nullable()->comment('过人成功');
            $table->unsignedSmallInteger('clearances')->nullable()->comment('解围');
            $table->unsignedSmallInteger('blocked_shots')->nullable()->comment('有效阻挡');
            $table->unsignedSmallInteger('interceptions')->nullable()->comment('拦截');
            $table->unsignedSmallInteger('tackles')->nullable()->comment('抢断');
            $table->unsignedSmallInteger('passes')->nullable()->comment('传球');
            $table->unsignedSmallInteger('passes_accuracy')->nullable()->comment('传球成功');
            $table->unsignedSmallInteger('key_passes')->nullable()->comment('关键传球');
            $table->unsignedSmallInteger('crosses')->nullable()->comment('传中球');
            $table->unsignedSmallInteger('crosses_accuracy')->nullable()->comment('传中球成功');
            $table->unsignedSmallInteger('long_balls')->nullable()->comment('长传');
            $table->unsignedSmallInteger('long_balls_accuracy')->nullable()->comment('成功长传');
            $table->unsignedSmallInteger('duels')->nullable()->comment('1对1拼抢');
            $table->unsignedSmallInteger('duels_won')->nullable()->comment('1对1拼抢成功');
            $table->unsignedSmallInteger('dispossessed')->nullable()->comment('丢球');
            $table->unsignedSmallInteger('fouls')->nullable()->comment('犯规');
            $table->unsignedSmallInteger('was_fouled')->nullable()->comment('被侵犯');
            $table->unsignedSmallInteger('saves')->nullable()->comment('扑救');
            $table->unsignedSmallInteger('punches')->nullable()->comment('拳击球');
            $table->unsignedSmallInteger('runs_out')->nullable()->comment('守门员出击');
            $table->unsignedSmallInteger('runs_out_succ')->nullable()->comment('守门员出击成功');
            $table->unsignedSmallInteger('good_high_claim')->nullable()->comment('高空出击');
            $table->boolean('rating')->unsigned()->nullable()->comment('评分，10为满分，为了避免浮点数影响，x100倍存储为整数，eg：计算评分为(760/100=7.60)');
            $table->unsignedInteger('updated_time')->nullable()->comment('纳米更新时间');
            $table->timestamps();
            $table->unique(['match_id', 'team_id'], 'unq_match_id_team_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sports_basketball_match_player_stats');
    }
}
