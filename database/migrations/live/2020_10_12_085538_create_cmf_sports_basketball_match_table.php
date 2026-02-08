<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateCmfSportsBasketballMatchTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement("
            CREATE TABLE `cmf_sports_basketball_match` (
              `id` int(10) NOT NULL AUTO_INCREMENT,
              `matchId` int(10) DEFAULT NULL COMMENT '比赛ID',
              `leagueId` int(10) DEFAULT NULL COMMENT '联赛/杯赛ID    ',
              `homeId` int(10) DEFAULT NULL COMMENT '主队ID',
              `awayId` int(10) DEFAULT NULL COMMENT '客队ID',
              `homeRank` smallint(10) DEFAULT NULL COMMENT '主队排名',
              `awayRank` smallint(10) DEFAULT NULL COMMENT '客队排名',
              `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '比赛状态:  0：比赛一场，1：未开赛，2：第一节，3：第一节完，4：第二节，5：第二节完，6：第三节，7：第三节完，8：第四节，9：加时，10：完场，11：中断，12：取消，13：延期，14：腰斩，15：待定',
              `matchStartTime` int(10) DEFAULT NULL COMMENT '比赛开始时间(时间戳) ',
              `matchConductTime` mediumint(10) DEFAULT NULL COMMENT '当前小节所剩时间(分钟:秒)',
              `homeScore` int(10) DEFAULT NULL COMMENT '主队全场比分',
              `awayScore` int(10) DEFAULT NULL COMMENT '客队全场比分',
              `nodeCount` smallint(5) DEFAULT NULL COMMENT '总节数',
              `homeNode1Score` smallint(5) DEFAULT NULL COMMENT '主队第一节比分 ',
              `homeNode2Score` smallint(5) DEFAULT NULL COMMENT '主队第二节比分 ',
              `homeNode3Score` smallint(5) DEFAULT NULL COMMENT '主队第三节比分 ',
              `homeNode4Score` smallint(5) DEFAULT NULL COMMENT '主队第四节比分 ',
              `homeNode5Score` smallint(5) DEFAULT NULL COMMENT '主队加时比分 ',
              `awayNode1Score` smallint(5) DEFAULT NULL COMMENT '客队第一节比分 ',
              `awayNode2Score` smallint(5) DEFAULT NULL COMMENT '客队第二节比分 ',
              `awayNode3Score` smallint(5) DEFAULT NULL COMMENT '客队第三节比分 ',
              `awayNode4Score` smallint(5) DEFAULT NULL COMMENT '客队第四节比分 ',
              `awayNode5Score` smallint(5) DEFAULT NULL COMMENT '客队加时比分 ',
              `letgoalHomeOdds` double(5,2) DEFAULT NULL COMMENT '让球即时主队赔率',
              `letgoalGoal` double(5,2) DEFAULT NULL COMMENT '让球即时盘口',
              `letgoalAwayOdds` double(5,2) DEFAULT NULL COMMENT '让球即时客队赔率',
              `letgoalIsEntertained` tinyint(1) DEFAULT '1' COMMENT '亚盘：是否封盘，0：表示没有盘口 ，1：未封盘  ，2：临时性封盘或停止走地 ',
              `europeHomeOdds` double(5,2) DEFAULT NULL COMMENT '欧赔即时主队赔率',
              `europeFlatOdds` double(5,2) DEFAULT NULL COMMENT '欧赔即时盘口',
              `europeAwayOdds` double(5,2) DEFAULT NULL COMMENT '欧赔即时客队赔率',
              `europeIsEntertained` tinyint(1) DEFAULT '1' COMMENT '欧赔：是否封盘，0：表示没有盘口，1：未封盘，2：临时性封盘或停止走地 ',
              `totalScoreHomeOdds` double(5,2) DEFAULT NULL COMMENT '大小球即时主队赔率  ',
              `totalScoreGoal` double(5,2) DEFAULT NULL COMMENT '大小球即时盘口  ',
              `totalScoreAwayOdds` double(5,2) DEFAULT NULL COMMENT '大小球即时客队赔率',
              `totalScoreIsEntertained` tinyint(1) DEFAULT '1' COMMENT '大小球：是否封盘，0：表示没有盘口，1：未封盘，2：临时性封盘或停止走地  ',
              `isSources` tinyint(1) DEFAULT NULL COMMENT '是否有情报,0：无，1：有',
              `isAnimation` tinyint(1) DEFAULT NULL COMMENT '是否有动画 （该字段无意思，不提供动画数据）,0：无，1：有',
              `isLive` tinyint(1) DEFAULT NULL COMMENT '是否有直播（购买直播信号可关联），无使用时，该字段无意义,0：无，1：有   ',
              `homeNodePauseCount` smallint(5) DEFAULT NULL COMMENT '主队剩暂停数',
              `awayNodePauseCount` smallint(5) DEFAULT NULL COMMENT '客队剩暂停数 ',
              `homeNodeFoulsCount` smallint(5) DEFAULT NULL COMMENT '主队犯规数',
              `awayNodeFoulsCount` smallint(5) DEFAULT NULL COMMENT '客队犯规数',
              `homeAssists` smallint(5) DEFAULT NULL COMMENT '主队助攻数',
              `homeSteals` smallint(5) DEFAULT NULL COMMENT '主队抢断数 ',
              `homeBlocks` smallint(5) DEFAULT NULL COMMENT '主队盖帽数',
              `awayAssists` smallint(5) DEFAULT NULL COMMENT '客队助攻数',
              `awaySteals` smallint(5) DEFAULT NULL COMMENT '客队抢断数 ',
              `awayBlocks` smallint(5) DEFAULT NULL COMMENT '客队盖帽数',
              `homeThreeCount` double(5,2) DEFAULT NULL COMMENT '主队3分球数',
              `homeTwoCount` double(5,2) DEFAULT NULL COMMENT '主队2分球数 ',
              `homeFreeCount` double(5,2) DEFAULT NULL COMMENT '主队罚球数',
              `homeFreeRate` double(5,2) DEFAULT NULL COMMENT '主队罚球命中率',
              `awayThreeCount` double(5,2) DEFAULT NULL COMMENT '客队3分球数',
              `awayTwoCount` double(5,2) DEFAULT NULL COMMENT '客队2分球数 ',
              `awayFreeCount` double(5,2) DEFAULT NULL COMMENT '客队罚球数',
              `awayFreeRate` double(5,2) DEFAULT NULL COMMENT '客队罚球命中率',
              `match_date` date DEFAULT NULL COMMENT '比赛日期',
              `created_at` datetime DEFAULT NULL COMMENT '创建时间',
              `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
              PRIMARY KEY (`id`),
              UNIQUE KEY `unq_matchId` (`matchId`),
              KEY `idx_leagueId` (`leagueId`),
              KEY `idx_homeId` (`homeId`),
              KEY `idx_awayId` (`awayId`),
              KEY `idx_matchStartTime` (`matchStartTime`),
              KEY `idx_match_date` (`match_date`),
              KEY `idx_state` (`state`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='篮球比赛表';
	    ");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cmf_sports_basketball_match');
	}

}
