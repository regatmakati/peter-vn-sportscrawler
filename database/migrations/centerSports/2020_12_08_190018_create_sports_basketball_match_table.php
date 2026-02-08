<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSportsBasketballMatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_basketball_match', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('season_id')->nullable()->index('idx_season_id')->comment('分类id');
            $table->unsignedInteger('competition_id')->nullable()->index('idx_competition_id')->comment('赛事id');
            $table->unsignedInteger('home_team_id')->nullable()->index('idx_home_team_id')->comment('主队id');
            $table->unsignedInteger('away_team_id')->nullable()->index('idx_away_team_id')->comment('客队id');
            $table->unsignedInteger('status_id')->nullable()->index('idx_status_id')->comment('|0|比赛异常，说明：暂未判断具体原因的异常比赛，可能但不限于：腰斩、取消等等，建议隐藏处理
|1|未开赛
|2|上半场
|3|中场
|4|下半场
|5|加时赛
|6|加时赛(弃用)
|7|点球决战
|8|完场
|9|推迟
|10|中断
|11|腰斩
|12|取消
|13|待定');
            $table->unsignedInteger('match_time')->nullable()->comment('比赛时间');
            $table->boolean('neutral')->unsigned()->nullable()->index('idx_neutral')->comment('是否中立场，1-是、0-否');
            $table->string('note')->nullable()->default('')->comment('备注');
            $table->string('home_scores')->nullable()->default('')->comment('比分字段说明：[比分(常规时间),半场比分,红牌,黄牌,角球，-1表示没有角球数据,加时比分(120分钟)，加时赛才有,点球大战比分，点球大战才有]');
            $table->string('away_scores')->nullable()->default('')->comment('比分字段说明：[比分(常规时间),半场比分,红牌,黄牌,角球，-1表示没有角球数据,加时比分(120分钟)，加时赛才有,点球大战比分，点球大战才有]');
            $table->string('home_position')->nullable()->default('')->comment('主队排名');
            $table->string('away_position')->nullable()->default('')->comment('客队排名');
            $table->string('coverage')->nullable()->default('')->comment('mlive: 是否有动画，1-是、0-否
intelligence: 是否有情报，1-是、0-否
lineup: 是否有阵容，1-是、0-否');
            $table->unsignedInteger('venue_id')->nullable()->index('idx_venue_id')->comment('场馆id，没有不存在');
            $table->string('round')->nullable()->default('')->comment('轮次，没有不存在
stage_id：阶段id
group_num: 第几组，1-A、2-B以此类推
round_num: 第几轮');
            $table->string('environment')->nullable()->default('')->comment('weather:天气id<br/>1:局部有云<br/>2:多云<br/>3:局部有云/雨<br/>4:雪<br/>5:晴<br/>6:阴有雨/局部有雷暴<br/>7:阴<br/>8:薄雾<br/>9:阴有雨<br/>10:多云有雨<br/>11:多云有雨/局部有雷暴<br/>12:局部有云/雨和雷暴<br/>13:雾
pressure:气压
temperature:温度
wind:风速
humidity:湿度');
            $table->string('live_url_1')->nullable()->default('')->comment('直播地址');
            $table->string('live_url_2')->nullable()->default('')->comment('直播地址');
            $table->string('live_url_3')->nullable()->default('')->comment('直播地址');
            $table->string('pc_link')->nullable()->default('')->comment('web版权直播地址');
            $table->string('mobile_link')->nullable()->default('')->comment('wap版权直播地址');
            $table->unsignedInteger('period_count')->nullable()->comment('比赛总节数');
            $table->string('trend', 10000)->nullable()->default('')->comment('比赛趋势');
            $table->boolean('kind')->unsigned()->nullable()->comment('类型id，1-常规赛、2-季后赛、3-季前赛、4-全明星、5-杯赛、0-无');
            $table->boolean('is_deleted')->unsigned()->nullable()->default(0)->index('idx_is_deleted')->comment('是否已删除：0正常，1已删除');
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
        Schema::dropIfExists('sports_basketball_match');
    }
}
