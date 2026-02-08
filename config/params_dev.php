<?php
/**
 * 配置
 */

/**
 * redis数据缓存时间
 */
$params['cache']['ttl'] = 10;

$params['recordpush']['url'] = 'rtmp://recordpush.xhfggzz.com';

$params['anchorplay']['url'] = 'https://anchorplay.yzcqc.com';

/**
 * 海鸥接口
 */
$haiOuUrl = 'http://119.188.248.116:8868';
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

//华图片地址
$params['domain']['image'] = "http://img.zbitcloud.com";
//体育直播地址
$params['domain']['live']['sports'] = "http://gameplay.hruui.com";

/**
 * 腾讯云直播配置信息
 */
$params['tencent']['live']['SecretId'] = "AKIDz0qQluRPhjVyEE9fV5PUAakFaJrHQwIq";
$params['tencent']['live']['SecretKey'] = "nSxHRZJDzf5Nfh7HBqHWfYBYdS1KNbld";
$params['tencent']['live']['pushDomain'] = "livepush.netipv6.com";
$params['tencent']['live']['pullDomain'] = "liveplay.netipv6.com";
$params['tencent']['live']['appName'] = "live";
$params['tencent']['live']['pullProtocol'] = "http";

//腾讯直播鉴权key
$params['tencent']['live']['key'] = "02019d98d6084be641cbf67ce3aa65fc";

$params['tencent']['cos']['secret_id'] = "AKID91HJ7hU3A1d2CODBnGTQARAZgW7S32yf";
$params['tencent']['cos']['secret_key'] = "ZuyeokPqDFZdQxEF20iASULwe5DLGwQi";
$params['tencent']['cos']['region'] = "ap-chengdu";
$params['tencent']['cos']['bucket'] = "real-hls-1303233598";

/**
 * 聊天服务器配置信息
 */
$params['chat']['chatUrl'] = '192.168.0.211';
$params['chat']['chatPort'] = 9511;
$params['chat']['socketSecretKey'] = 'f7s8v8bnm9ad54c5badda7d6304r0higfuad';

/**
 * 纳米体育接口
 */
$naMiUrl = 'http://119.188.248.116:8869';
$params['naMi']['user'] = 'Roger007';
$params['naMi']['secret'] = 'c4d29d7923b8fde5142122eb3fab3c49';
//纳米体育直播地址
$naMiLiveUrl = 'http://119.188.248.116:8870';
$params['naMi']['live_url'] = "{$naMiLiveUrl}/pushurl_v4";

//分类列表
$params['naMi']['football']['category'] = "{$naMiUrl}/api/v4/football/category/list";
//国家列表
$params['naMi']['football']['country'] = "{$naMiUrl}/api/v4/football/country/list";
//赛事列表
$params['naMi']['football']['competition'] = "{$naMiUrl}/api/v4/football/competition/list";
//赛事规则列表
$params['naMi']['football']['competition_rule'] = "{$naMiUrl}/api/v4/football/competition/rule/list";
//球队列表
$params['naMi']['football']['team'] = "{$naMiUrl}/api/v4/football/team/list";
//球员列表
$params['naMi']['football']['player'] = "{$naMiUrl}/api/v4/football/player/list";
//教练列表
$params['naMi']['football']['manager'] = "{$naMiUrl}/api/v4/football/manager/list";
//裁判列表
$params['naMi']['football']['referee'] = "{$naMiUrl}/api/v4/football/referee/list";
//场馆列表
$params['naMi']['football']['venue'] = "{$naMiUrl}/api/v4/football/venue/list";
//荣誉列表
$params['naMi']['football']['honor'] = "{$naMiUrl}/api/v4/football/honor/list";
//比赛列表
$params['naMi']['football']['match'] = "{$naMiUrl}/api/v4/football/match/list";
//比赛前后15天列表
$params['naMi']['football']['match_daily'] = "{$naMiUrl}/api/v4/football/match/diary";
//获取实时统计数据
$params['naMi']['football']['detail_live'] = "{$naMiUrl}/api/sports/football/match/detail_live";
//获取比赛趋势详情
$params['naMi']['football']['trend'] = "{$naMiUrl}/api/v4/football/match/trend/detail";
//获取版权比赛直播地址
$params['naMi']['football']['livePath'] = "{$naMiUrl}/api/sports/stream/urls_free";
//获取版权比赛直播地址
$params['naMi']['football']['video_collection'] = "{$naMiUrl}/api/video_collection/by_match";
//获取版权比赛直播地址
$params['naMi']['football']['deleted'] = "{$naMiUrl}/api/v4/football/deleted";
//获取伤停列表
$params['naMi']['football']['injury'] = "{$naMiUrl}/api/v4/football/injury/list";
//获取比赛阵容
$params['naMi']['football']['lineup'] = "{$naMiUrl}/api/v4/football/match/lineup/detail";
//获取赛季列表
$params['naMi']['football']['season'] = "{$naMiUrl}/api/v4/football/season/list";
//获取分析数据
$params['naMi']['football']['analysis'] = "{$naMiUrl}/api/sports/football/match/analysis";
//获取历史同赔数据
$params['naMi']['football']['compensation'] = "{$naMiUrl}/api/v4/football/compensation/list";
//获取比赛历史统计数据
$params['naMi']['football']['history'] = "{$naMiUrl}/api/v4/football/match/live/history";

//分类列表
$params['naMi']['basketball']['category'] = "{$naMiUrl}/api/v4/basketball/category/list";
//国家列表
$params['naMi']['basketball']['country'] = "{$naMiUrl}/api/v4/basketball/country/list";
//赛事列表
$params['naMi']['basketball']['competition'] = "{$naMiUrl}/api/v4/basketball/competition/list";
//球队列表
$params['naMi']['basketball']['team'] = "{$naMiUrl}/api/v4/basketball/team/list";
//球员列表
$params['naMi']['basketball']['player'] = "{$naMiUrl}/api/v4/basketball/player/list";
//场馆列表
$params['naMi']['basketball']['venue'] = "{$naMiUrl}/api/v4/basketball/venue/list";
//荣誉列表
$params['naMi']['basketball']['honor'] = "{$naMiUrl}/api/v4/basketball/honor/list";
//比赛列表
$params['naMi']['basketball']['match'] = "{$naMiUrl}/api/v4/basketball/match/list";
//比赛前后15天列表
$params['naMi']['basketball']['match_daily'] = "{$naMiUrl}/api/v4/basketball/match/diary";
//获取实时统计数据
$params['naMi']['basketball']['detail_live'] = "{$naMiUrl}/api/sports/basketball/match/detail_live";
//获取比赛趋势详情
$params['naMi']['basketball']['trend'] = "{$naMiUrl}/api/v4/basketball/match/trend/detail";
//获取版权比赛直播地址
$params['naMi']['basketball']['livePath'] = "{$naMiUrl}/api/sports/stream/urls_free";
//获取版权比赛直播地址
$params['naMi']['basketball']['deleted'] = "{$naMiUrl}/api/v4/basketball/deleted";
//获取伤停列表
$params['naMi']['basketball']['injury'] = "{$naMiUrl}/api/v4/basketball/injury/list";
//获取比赛阵容
$params['naMi']['basketball']['squad'] = "{$naMiUrl}/api/v4/basketball/team/squad/list";
//获取赛季列表
$params['naMi']['basketball']['season'] = "{$naMiUrl}/api/v4/football/season/list";
//获取分析数据
$params['naMi']['basketball']['analysis'] = "{$naMiUrl}/api/sports/basketball/match/analysis";
//获取历史同赔数据
$params['naMi']['basketball']['compensation'] = "{$naMiUrl}/api/v4/basketball/compensation/list";
//获取比赛历史统计数据
$params['naMi']['basketball']['history'] = "{$naMiUrl}/api/v4/basketball/match/live/history";

//纳米电竞接口
$naMiGameUrl = 'http://119.188.248.116:8871';
$params['naMi']['game']['user'] = 'Roger666';
$params['naMi']['game']['secret'] = '12dfb67fb5793d252df9ac9792e63fe1';
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
