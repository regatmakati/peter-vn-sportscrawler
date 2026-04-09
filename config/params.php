<?php
/**
 * 配置
 */

/**
 * redis数据缓存时间
 */
$params['cache']['ttl'] = 10;


/**
 * 海鸥接口
 */
$haiOuUrl = 'https://open.sportnanoapi.com';//'http://119.188.248.116:8868';
//足球赛程
$params['haiOu']['football']['willList'] = "{$haiOuUrl}/api/fb/match/willList";
//足球赛果
$params['haiOu']['football']['haveList'] = "{$haiOuUrl}/api/fb/match/haveList";
//足球阵容
$params['haiOu']['football']['getMatchTeamPlayer'] = "{$haiOuUrl}/api/fb/matchAnalysis/getMatchTeamPlayer";
//足球文字直播
$params['haiOu']['football']['getMatchTextLive'] = "{$haiOuUrl}/api/fb/matchAnalysis/getMatchTextLive";
//足球比赛即时数据
$params['haiOu']['football']['timelyList'] = "{$haiOuUrl}/api/fb/match/timelyList";
//足球比赛详情
$params['haiOu']['football']['liveMatchData'] = "{$haiOuUrl}/api/fb/matchInner/liveMatchData";

//篮球赛程
$params['haiOu']['basketball']['willList'] = "{$haiOuUrl}/api/bb/match/willList";
//篮球赛果
$params['haiOu']['basketball']['haveList'] = "{$haiOuUrl}/api/bb/match/haveList";
//篮球比赛直播数据
$params['haiOu']['basketball']['liveMatchData'] = "{$haiOuUrl}/api/bb/matchInner/liveMatchData";
//篮球文字直播
$params['haiOu']['basketball']['textLiveData'] = "{$haiOuUrl}/api/bb/matchInner/textLiveData";
//篮球统计数据
$params['haiOu']['basketball']['statisticData'] = "{$haiOuUrl}/api/bb/matchInner/statisticData";
//篮球比赛即时数据
$params['haiOu']['basketball']['timelyList'] = "{$haiOuUrl}/api/bb/match/timelyList";


/**
 * 直播接口
 */
$liveUrl = "http://119.188.248.116:6601";
$params['retailLive']['accessKey'] = "La5RMtyQ8A";
$params['retailLive']['accessSecret'] = "nWUpM2cqbveJUZUj";
//正在进行的和两小时内开赛的比赛的直播数据接口
$params['retailLive']['live'] = "{$liveUrl}/retailLive/live";
//两小时后至七天内开赛的比赛的直播数据接口
$params['retailLive']['future'] = "{$liveUrl}/retailLive/future";
/**
 * 直播url前缀
 */
$params['live_url']['prefix'] = "rtmp://zbpush.khpnq.cn";

/**
 * 华图片地址
 */
$params['domain']['image'] = "https://live-peter-ii.obs.ap-southeast-1.myhuaweicloud.com";

/**
 * 视频缓存服务器地址
 */
$params['m3u8']['server']['1'] = 'https://video.shandiy.com';
$params['m3u8']['server']['2'] = 'https://video.diannaosm.com';


/**
 * 腾讯云直播配置信息
 */
$params['tencent']['live']['SecretId'] = "IKIDb5MRJlHiyRnxxpUxXw86tX2OZZL1Vnup";
$params['tencent']['live']['SecretKey'] = "Qjz1pPlwvjKitqa6g4GG3e1A6tTp0ykI";
$params['tencent']['live']['pushDomain'] = "zbpush.frgat.cn";
$params['tencent']['live']['pullDomain'] = "zbpull.frgat.cn";
$params['tencent']['live']['appName'] = "live";
$params['tencent']['live']['pullProtocol'] = "https";

//腾讯直播鉴权key
$params['tencent']['live']['key'] = "68f8959b7850a5ab7ab8c45877473284";

$params['tencent']['cos']['secret_id'] = "AKID91HJ7hU3A1d2CODBnGTQARAZgW7S32yf";
$params['tencent']['cos']['secret_key'] = "ZuyeokPqDFZdQxEF20iASULwe5DLGwQi";
$params['tencent']['cos']['region'] = "ap-chengdu";
$params['tencent']['cos']['bucket'] = "real-hls-1303233598";

/**
 * 聊天服务器配置信息
 */
$params['chat']['chatUrl'] = '172.19.0.10';
$params['chat']['chatPort'] = 9511;
$params['chat']['socketSecretKey'] = 'f7s8v8bnm9ad54c5badda7d6304r0higfuad';

/**
 * 纳米体育接口
 */
$naMiUrl = 'https://open.sportnanoapi.com';//'http://119.188.248.116:8869';
$params['naMi']['user'] = 'nalsince';
$params['naMi']['secret'] = '85354e61faa389fc488051eb144f4d89';
//纳米体育直播地址
$naMiLiveUrl = 'https://open.sportnanoapi.com';//'http://119.188.248.116:8870';
$params['naMi']['live_url'] = "{$naMiLiveUrl}/pushurl_v4";

//分类列表
$params['naMi']['football']['category'] = "{$naMiUrl}/api/v5/football/category/list";
//国家列表
$params['naMi']['football']['country'] = "{$naMiUrl}/api/v5/football/country/list";
//赛事列表
$params['naMi']['football']['competition'] = "{$naMiUrl}/api/v5/football/competition/list";
//赛事规则列表
$params['naMi']['football']['competition_rule'] = "{$naMiUrl}/api/v5/football/competition/rule/list";
//获取赛季列表
$params['naMi']['football']['season'] = "{$naMiUrl}/api/v5/football/season/list";
//球队列表
$params['naMi']['football']['team'] = "{$naMiUrl}/api/v5/football/team/list";
//球员列表
$params['naMi']['football']['player'] = "{$naMiUrl}/api/v5/football/player/list";
//教练列表
$params['naMi']['football']['manager'] = "{$naMiUrl}/api/v5/football/coach/list";
//裁判列表
$params['naMi']['football']['referee'] = "{$naMiUrl}/api/v5/football/referee/list";
//场馆列表
$params['naMi']['football']['venue'] = "{$naMiUrl}/api/v5/football/venue/list";
//荣誉列表
$params['naMi']['football']['honor'] = "{$naMiUrl}/api/v5/football/honor/list";


//比赛列表
$params['naMi']['football']['match'] = "{$naMiUrl}/api/v5/football/recent/match/list";
//比赛前后15天列表
$params['naMi']['football']['match_daily'] = "{$naMiUrl}/api/v5/football/match/schedule/diary";
//获取实时统计数据
$params['naMi']['football']['detail_live'] = "{$naMiUrl}/api/v5/football/match/live";
//获取比赛趋势详情
$params['naMi']['football']['trend'] = "{$naMiUrl}/api/v5/football/match/trend/detail";
//获取版权比赛直播地址
$params['naMi']['football']['livePath'] = "{$naMiUrl}/api/v5/football/match/stream/urls_free";
//获取版权比赛集锦录像地址
$params['naMi']['football']['video_collection'] = "{$naMiUrl}/api/v5/football/match/stream/video_collection";
//获取删除数据
$params['naMi']['football']['deleted'] = "{$naMiUrl}/api/v5/football/deleted";
//获取伤停列表
$params['naMi']['football']['injury'] = "{$naMiUrl}/api/v5/football/team/injury/list";
//获取比赛阵容
$params['naMi']['football']['lineup'] = "{$naMiUrl}/api/v5/football/match/lineup/detail";
//获取比赛分析数据
$params['naMi']['football']['analysis'] = "{$naMiUrl}/api/v5/football/match/analysis";
//获取比赛历史同赔统计列表
$params['naMi']['football']['compensation'] = "{$naMiUrl}/api/v5/football/compensation/list";
//获取历史比赛统计数据
$params['naMi']['football']['history'] = "{$naMiUrl}/api/v5/football/match/live/history";

//获取分类列表
$params['naMi']['basketball']['category'] = "{$naMiUrl}/api/v5/basketball/category/list";
//获取国家列表
$params['naMi']['basketball']['country'] = "{$naMiUrl}/api/v5/basketball/country/list";
//获取赛事列表
$params['naMi']['basketball']['competition'] = "{$naMiUrl}/api/v5/basketball/competition/list";
//球队列表
$params['naMi']['basketball']['team'] = "{$naMiUrl}/api/v5/basketball/team/list";
//获取球员列表
$params['naMi']['basketball']['player'] = "{$naMiUrl}/api/v5/basketball/player/list";
//获取场馆列表
$params['naMi']['basketball']['venue'] = "{$naMiUrl}/api/v5/basketball/venue/list";
//获取荣誉列表
$params['naMi']['basketball']['honor'] = "{$naMiUrl}/api/v5/basketball/honor/list";
//获取赛季列表
$params['naMi']['basketball']['season'] = "{$naMiUrl}/api/v5/basketball/season/list";
//获取变动比赛列表
$params['naMi']['basketball']['match'] = "{$naMiUrl}/api/v5/basketball/recent/match/list";
//比赛前后15天列表
$params['naMi']['basketball']['match_daily'] = "{$naMiUrl}/api/v5/basketball/match/schedule/diary";
//获取实时统计数据
$params['naMi']['basketball']['detail_live'] = "{$naMiUrl}/api/v5/basketball/match/live";
//获取比赛趋势详情
$params['naMi']['basketball']['trend'] = "{$naMiUrl}/api/v5/basketball/match/trend/detail";
//获取版权比赛直播地址
$params['naMi']['basketball']['livePath'] = "{$naMiUrl}/api/v5/basketball/match/stream/urls_free";
//获取删除数据
$params['naMi']['basketball']['deleted'] = "{$naMiUrl}/api/v5/basketball/deleted";
//获取球队伤停列表
$params['naMi']['basketball']['injury'] = "{$naMiUrl}/api/v5/basketball/team/injury/list";
//获取球队阵容列表
$params['naMi']['basketball']['squad'] = "{$naMiUrl}/api/v5/basketball/team/squad/list";
//获取比赛分析数据
$params['naMi']['basketball']['analysis'] = "{$naMiUrl}/api/v5/basketball/match/analysis";
//获取比赛历史同赔统计列表
$params['naMi']['basketball']['compensation'] = "{$naMiUrl}/api/v5/basketball/compensation/list";
//获取历史比赛统计数据
$params['naMi']['basketball']['history'] = "{$naMiUrl}/api/v5/basketball/match/live/history";

//纳米电竞接口
$naMiGameUrl = 'https://open.sportnanoapi.com';//'http://119.188.248.116:8871';
$params['naMi']['game']['user'] = 'hzzj';//'Roger666';
$params['naMi']['game']['secret'] = '5f2fb73e4544b29eac54256697a5c23c';//'12dfb67fb5793d252df9ac9792e63fe1';
//纳米电竞直播地址
$params['naMi']['game']['live_url'] = "{$naMiLiveUrl}/esports_pushurl";

//国家
$params['naMi']['dota2']['country'] = "{$naMiGameUrl}/v1/dota2/country";
//赛事
$params['naMi']['dota2']['tournament'] = "{$naMiGameUrl}/v1/dota2/tournament";
//选手
$params['naMi']['dota2']['player'] = "{$naMiGameUrl}/v1/dota2/player";
//战队
$params['naMi']['dota2']['team'] = "{$naMiGameUrl}/v1/dota2/team";
//赛区
$params['naMi']['dota2']['region'] = "{$naMiGameUrl}/v1/dota2/region";
//英雄
$params['naMi']['dota2']['hero'] = "{$naMiGameUrl}/v1/dota2/hero";
//英雄天赋
$params['naMi']['dota2']['hero_rune'] = "{$naMiGameUrl}/v1/dota2/hero/rune";
//英雄技能
$params['naMi']['dota2']['hero_spell'] = "{$naMiGameUrl}/v1/dota2/hero/spell";
//装备列表
$params['naMi']['dota2']['equipment'] = "{$naMiGameUrl}/v1/dota2/equipment";
//比赛列表
$params['naMi']['dota2']['match'] = "{$naMiGameUrl}/v1/dota2/match";
//比赛单局
$params['naMi']['dota2']['match_single'] = "{$naMiGameUrl}/v1/dota2/match/single";
//比赛单局选手列表
$params['naMi']['dota2']['match_single_player_stat'] = "{$naMiGameUrl}/v1/dota2/match/single/player/stat";
//赛事比赛
$params['naMi']['dota2']['tournament_match'] = "{$naMiGameUrl}/v1/dota2/tournament/match";
//实时比赛
$params['naMi']['dota2']['match_live'] = "{$naMiGameUrl}/v1/dota2/match/live";
//比赛视频录像、动画
$params['naMi']['dota2']['stream'] = "{$naMiGameUrl}/v1/dota2/match/stream";
//比赛视频录像、动画
$params['naMi']['dota2']['delete'] = "{$naMiGameUrl}/v1/dota2/delete";
//实时指数
$params['naMi']['dota2']['odds_live'] = "{$naMiGameUrl}/v1/dota2/odds/live";
//单场指数
$params['naMi']['dota2']['odds_history'] = "{$naMiGameUrl}/v1/dota2/odds/history";
//阶段列表
$params['naMi']['dota2']['stage'] = "{$naMiGameUrl}/v1/dota2/stage";
//赛事战队
$params['naMi']['dota2']['tournament_team'] = "{$naMiGameUrl}/v1/dota2/tournament/team";
//赛事轮次
$params['naMi']['dota2']['tournament_round'] = "{$naMiGameUrl}/v1/dota2/tournament/round";
//赛事对阵
$params['naMi']['dota2']['tournament_pvp'] = "{$naMiGameUrl}/v1/dota2/tournament/pvp";
//赛事对阵连线
$params['naMi']['dota2']['tournament_pvp_line'] = "{$naMiGameUrl}/v1/dota2/tournament/pvp/line";
//赛事积分列表
$params['naMi']['dota2']['tournament_rank'] = "{$naMiGameUrl}/v1/dota2/tournament/rank";


return $params;
