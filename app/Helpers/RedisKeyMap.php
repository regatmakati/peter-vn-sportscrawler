<?php


namespace App\Helpers;


class RedisKeyMap
{
    public static function getFootballMatchTextLive($matchId)
    {
        return "football_text_live_{$matchId}";
    }

    public static function getBasketballMatchTextLive($matchId)
    {
        return "basketball_text_live_{$matchId}";
    }

    public static function getFootballMatchAllList($page)
    {
        return "football_match_all_list_{$page}";
    }

    public static function getBasketballMatchAllList($page)
    {
        return "basketball_match_all_list_{$page}";
    }

    public static function getFootballMatchPlayingList($page)
    {
        return "football_match_playing_list_{$page}";
    }

    public static function getBasketballMatchPlayingList($page)
    {
        return "basketball_match_playing_list_{$page}";
    }

    public static function getFootballMatchListByDate($page, $date, $action)
    {
        return "football_match_schedule_list_{$page}_{$date}_{$action}";
    }

    public static function getBasketballMatchListByDate($page, $date, $action)
    {
        return "basketball_match_schedule_list_{$page}_{$date}_{$action}";
    }

    public static function getFootballMatch($matchId)
    {
        return "football_match_{$matchId}";
    }

    public static function getBasketballMatch($matchId)
    {
        return "basketball_match_{$matchId}";
    }

    public static function getLiveRandomList()
    {
        return "live_random_list}";
    }

    public static function getPcLiveList($page, $limit, $liveClassId)
    {
        return "pc_live_list_{$page}_{$limit}_{$liveClassId}";
    }

    public static function getConfig($optionName)
    {
        return "config_{$optionName}";
    }

    public static function getSensitiveWords(){
        return "sensitive_words";
    }

    public static function getDota2MatchList($page, $action)
    {
        return "dota2_match_all_list_{$page}_{$action}";
    }

    //纳米数据RedisKeyMap

    public static function getFootballMatchTextLiveV2($matchId)
    {
        return "football_text_live_v2_{$matchId}";
    }

    public static function getBasketballMatchTextLiveV2($matchId)
    {
        return "basketball_text_live_v2_{$matchId}";
    }

    public static function getFootballMatchAllListV2($page)
    {
        return "football_match_all_list_v2_{$page}";
    }

    public static function getFootballMatchAllListV3($page)
    {
        return "football_match_all_list_v3_{$page}";
    }

    public static function getBasketballMatchAllListV2($page)
    {
        return "basketball_match_all_list_v2_{$page}";
    }

    public static function getBasketballMatchAllListV3($page)
    {
        return "basketball_match_all_list_v3_{$page}";
    }

    public static function getFootballMatchPlayingListV2($page)
    {
        return "football_match_playing_list_v2_{$page}";
    }

    public static function getFootballMatchPlayingListV3($page)
    {
        return "football_match_playing_list_v3_{$page}";
    }

    public static function getBasketballMatchPlayingListV2($page)
    {
        return "basketball_match_playing_list_v2_{$page}";
    }

    public static function getBasketballMatchPlayingListV3($page)
    {
        return "basketball_match_playing_list_v3_{$page}";
    }


    public static function getFootballMatchListByDateV2($page, $date, $action)
    {
        return "football_match_schedule_list_v2_{$page}_{$date}_{$action}";
    }


    public static function getFootballMatchListByDateV3($page, $date, $action)
    {
        return "football_match_schedule_list_v3_{$page}_{$date}_{$action}";
    }



    public static function getFootballMatchListByHotV3($page)
    {
        return "football_match_schedule_hot_list_v3_{$page}";
    }


    public static function getBasketballMatchListByHotV3($page)
    {
        return "basketball_match_schedule_hot_list_v3_{$page}";
    }


    public static function getBasketballMatchListByDateV2($page, $date, $action)
    {
        return "basketball_match_schedule_list_v2_{$page}_{$date}_{$action}";
    }


    public static function getBasketballMatchListByDateV3($page, $date, $action)
    {
        return "basketball_match_schedule_list_v3_{$page}_{$date}_{$action}";
    }

    public static function getFootballMatchV2($matchId)
    {
        return "football_match_v2_{$matchId}";
    }

    public static function getBasketballMatchV2($matchId)
    {
        return "basketball_match_v2_{$matchId}";
    }

    public static function getLiveRandomListV2()
    {
        return "live_random_list_v2";
    }

    public static function getPcLiveListV2($page, $limit, $liveClassId)
    {
        return "pc_live_list_v2_{$page}_{$limit}_{$liveClassId}";
    }

    public static function getDota2MatchListV2($page, $action)
    {
        return "dota2_match_all_list_v2_{$page}_{$action}";
    }

}
