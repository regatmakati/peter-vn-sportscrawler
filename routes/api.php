<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->get('v1/live/getRandomList', 'App\Http\Controllers\LiveController@getRandomList');
    $api->post('v1/live/getRandomList', 'App\Http\Controllers\LiveController@getRandomList');
    $api->get('v1/live/getPcLiveList', 'App\Http\Controllers\LiveController@getPcLiveList');
    $api->post('v1/live/getPcLiveList', 'App\Http\Controllers\LiveController@getPcLiveList');

    $api->get('v2/live/getLiveVideoList', 'App\Http\Controllers\LiveController@getLiveVideoList');
    $api->post('v2/live/getLiveVideoList', 'App\Http\Controllers\LiveController@getLiveVideoList');
	
    $api->get('v1/football/getMatchAllList', 'App\Http\Controllers\FootballV2Controller@getMatchAllList');
    $api->post('v1/football/getMatchAllList', 'App\Http\Controllers\FootballV2Controller@getMatchAllList');
    $api->get('v1/basketball/getMatchAllList', 'App\Http\Controllers\BasketballV2Controller@getMatchAllList');
    $api->post('v1/basketball/getMatchAllList', 'App\Http\Controllers\BasketballV2Controller@getMatchAllList');

    $api->get('v1/basketball/getSlideEvents', 'App\Http\Controllers\BasketballV2Controller@getSlideEvents');
    $api->post('v1/basketball/getSlideEvents', 'App\Http\Controllers\BasketballV2Controller@getSlideEvents');
	
    $api->get('v1/football/getMatchPlayingList', 'App\Http\Controllers\FootballController@getMatchPlayingList');
    $api->post('v1/football/getMatchPlayingList', 'App\Http\Controllers\FootballController@getMatchPlayingList');
    $api->get('v1/basketball/getMatchPlayingList', 'App\Http\Controllers\BasketballController@getMatchPlayingList');
    $api->post('v1/basketball/getMatchPlayingList', 'App\Http\Controllers\BasketballController@getMatchPlayingList');

    $api->get('v1/football/getMatchListByDate', 'App\Http\Controllers\FootballController@getMatchListByDate');
    $api->post('v1/football/getMatchListByDate', 'App\Http\Controllers\FootballController@getMatchListByDate');
    $api->get('v1/basketball/getMatchListByDate', 'App\Http\Controllers\BasketballController@getMatchListByDate');
    $api->post('v1/basketball/getMatchListByDate', 'App\Http\Controllers\BasketballController@getMatchListByDate');

    $api->get('v1/football/getMatch', 'App\Http\Controllers\FootballV2Controller@getMatch');
    $api->post('v1/football/getMatch', 'App\Http\Controllers\FootballV2Controller@getMatch');
    $api->get('v1/basketball/getMatch', 'App\Http\Controllers\BasketballV2Controller@getMatch');
    $api->post('v1/basketball/getMatch', 'App\Http\Controllers\BasketballV2Controller@getMatch');

    /**
     * 统计
     */
    $api->get('v1/statistics/click', 'App\Http\Controllers\StatisticsController@click');
    $api->post('v1/statistics/click', 'App\Http\Controllers\StatisticsController@click');

    //纳米体育数据接口route
    $api->get('v2/football/getMatchAllList', 'App\Http\Controllers\FootballV2Controller@getMatchAllList');
    $api->post('v2/football/getMatchAllList', 'App\Http\Controllers\FootballV2Controller@getMatchAllList');
    $api->get('v2/basketball/getMatchAllList', 'App\Http\Controllers\BasketballV2Controller@getMatchAllList');
    $api->post('v2/basketball/getMatchAllList', 'App\Http\Controllers\BasketballV2Controller@getMatchAllList');

    $api->get('v2/basketball/getSlideEvents', 'App\Http\Controllers\BasketballV2Controller@getSlideEvents');
    $api->post('v2/basketball/getSlideEvents', 'App\Http\Controllers\BasketballV2Controller@getSlideEvents');
	
    $api->get('v2/football/getMatchPlayingList', 'App\Http\Controllers\FootballV2Controller@getMatchPlayingList');
    $api->post('v2/football/getMatchPlayingList', 'App\Http\Controllers\FootballV2Controller@getMatchPlayingList');
    $api->get('v2/basketball/getMatchPlayingList', 'App\Http\Controllers\BbasketballV2Controller@getMatchPlayingList');
    $api->post('v2/basketball/getMatchPlayingList', 'App\Http\Controllers\BasketballV2Controller@getMatchPlayingList');

    $api->get('v2/football/getMatchListByDate', 'App\Http\Controllers\FootballV2Controller@getMatchListByDate');
    $api->post('v2/football/getMatchListByDate', 'App\Http\Controllers\FootballV2Controller@getMatchListByDate');
    $api->get('v2/basketball/getMatchListByDate', 'App\Http\Controllers\BasketballV2Controller@getMatchListByDate');
    $api->post('v2/basketball/getMatchListByDate', 'App\Http\Controllers\BasketballV2Controller@getMatchListByDate');

    $api->get('v2/football/getMatch', 'App\Http\Controllers\FootballV2Controller@getMatch');
    $api->post('v2/football/getMatch', 'App\Http\Controllers\FootballV2Controller@getMatch');
    $api->get('v2/basketball/getMatch', 'App\Http\Controllers\BasketballV2Controller@getMatch');
    $api->post('v2/basketball/getMatch', 'App\Http\Controllers\BasketballV2Controller@getMatch');



    //越南改版的比赛
    $api->any('v3/football/getMatchAllList', 'App\Http\Controllers\FootballV3Controller@getMatchAllList');
    $api->any('v3/football/getMatchListByHot', 'App\Http\Controllers\FootballV3Controller@getMatchListByHot');
    $api->any('v3/football/getMatchPlayingList', 'App\Http\Controllers\FootballV3Controller@getMatchPlayingList');
    $api->any('v3/football/getMatchListByDate', 'App\Http\Controllers\FootballV3Controller@getMatchListByDate');


    $api->any('v3/basketball/getMatchAllList', 'App\Http\Controllers\BasketballV3Controller@getMatchAllList');
    $api->any('v3/basketball/getMatchListByHot', 'App\Http\Controllers\BasketballV3Controller@getMatchListByHot');
    $api->any('v3/basketball/getMatchPlayingList', 'App\Http\Controllers\BasketballV3Controller@getMatchPlayingList');
    $api->any('v3/basketball/getMatchListByDate', 'App\Http\Controllers\BasketballV3Controller@getMatchListByDate');


});

