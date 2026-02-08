<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateCmfSportsFootballMatchTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    DB::statement("
            CREATE TABLE `cmf_sports_football_match` (
              `id` int(10) NOT NULL AUTO_INCREMENT,
              `matchId` int(10) NOT NULL COMMENT '比赛ID',
              `leagueId` int(10) NOT NULL COMMENT '联赛/杯赛ID    ',
              `matchStartTime` int(10) DEFAULT NULL COMMENT '比赛开始时间(时间戳) ',
              `state` tinyint(1) DEFAULT '0' COMMENT '比赛状态:-14:推迟，-13:中断，-12:腰斩，-11:待定，-10:取消，-1.完场，0:未开始，1:上半场，2:中场，3:下半场，4:加时，5:点球 ',
              `isNeutral` tinyint(1) DEFAULT NULL COMMENT '是否中立场，1：是，2：否',
              `locationCn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '比赛场地(中文,繁体也使用这个字段)',
              `locationEn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '比赛场地(英文)',
              `homeId` int(10) NOT NULL COMMENT '主队ID',
              `awayId` int(10) NOT NULL COMMENT '客队ID',
              `homeScore` int(10) DEFAULT NULL COMMENT '主队全场比分',
              `awayScore` int(10) DEFAULT NULL COMMENT '客队全场比分',
              `homeHalfScore` int(10) DEFAULT NULL COMMENT '主队半场比分',
              `awayHalfScore` int(10) DEFAULT NULL COMMENT '客队半场比分',
              `homeCornerNum` int(10) DEFAULT NULL COMMENT '主队角球数 ',
              `awayCornerNum` int(10) DEFAULT NULL COMMENT '客队角球数 ',
              `extraFirstKick` tinyint(1) DEFAULT NULL COMMENT '加时: 先开球方，1：主队先开球，2：客队先开球',
              `extraNormalTime` int(10) DEFAULT NULL COMMENT '加时: 常规时间',
              `extraNormalScore` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '加时: 常规时间的比分 ',
              `extraTwoLegsScore` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '加时: 两回合总比分, 该字段仅在比赛有加时、点球的情况才更新    ',
              `extraType` tinyint(1) DEFAULT NULL COMMENT '加时: 加时阶段类型，1:120分钟，2：加时，3：加时中',
              `extraScore` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '加时: 加时阶段比分 ',
              `extraPenaltyKickScore` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '加时: 点球大战比分    ',
              `extraWin` tinyint(1) DEFAULT NULL COMMENT '加时: 获胜方，1：主队获胜，2：客队获胜',
              `letgoalHomeOdds` double(5,2) DEFAULT NULL COMMENT '让球即时主队赔率',
              `letgoalGoal` double(5,2) DEFAULT NULL COMMENT '让球即时盘口',
              `letgoalAwayOdds` double(5,2) DEFAULT NULL COMMENT '让球即时客队赔率',
              `letgoalIsEntertained` tinyint(1) DEFAULT '1' COMMENT '亚盘：是否封盘,1：默认，2：临时性封盘或停止走地 ',
              `europeHomeOdds` double(5,2) DEFAULT NULL COMMENT '欧赔即时主队赔率',
              `europeFlatOdds` double(5,2) DEFAULT NULL COMMENT '欧赔即时盘口',
              `europeAwayOdds` double(5,2) DEFAULT NULL COMMENT '欧赔即时客队赔率',
              `europeIsEntertained` tinyint(1) DEFAULT '1' COMMENT '欧赔：是否封盘,1：默认，2：临时性封盘或停止走地 ',
              `totalScoreHomeOdds` double(5,2) DEFAULT NULL COMMENT '大小球即时主队赔率  ',
              `totalScoreGoal` double(5,2) DEFAULT NULL COMMENT '大小球即时盘口  ',
              `totalScoreAwayOdds` double(5,2) DEFAULT NULL COMMENT '大小球即时客队赔率',
              `totalScoreIsEntertained` tinyint(1) DEFAULT '1' COMMENT '大小球：是否封盘, 1：默认，2：临时性封盘或停止走地   ',
              `hasSources` tinyint(1) DEFAULT NULL COMMENT '是否有情报,0：无，1：有',
              `hasAnimation` tinyint(1) DEFAULT NULL COMMENT '是否有动画 （该字段无意思，不提供动画数据）,0：无，1：有',
              `hasLive` tinyint(1) DEFAULT NULL COMMENT '是否有直播（购买直播信号可关联），无使用时，该字段无意义,0：无，1：有   ',
              `weatherEn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '	天气（英文）',
              `weatherCn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '天气（中文）',
              `weatherIcon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '天气图标',
              `temperature` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '温度',
              `match_date` date DEFAULT NULL COMMENT '比赛日期',
              `created_at` datetime DEFAULT NULL COMMENT '创建时间',
              `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
              PRIMARY KEY (`id`),
              UNIQUE KEY `unq_matchId` (`matchId`),
              KEY `idx_state` (`state`) USING BTREE,
              KEY `idx_matchStartTime` (`matchStartTime`),
              KEY `idx_leagueId` (`leagueId`),
              KEY `idx_homeId` (`homeId`),
              KEY `idx_awayId` (`awayId`),
              KEY `idx_match_date` (`match_date`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='足球比赛表';
	    ");
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cmf_sports_football_match');
	}

}
