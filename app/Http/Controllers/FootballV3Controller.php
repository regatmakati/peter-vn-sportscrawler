<?php

namespace App\Http\Controllers;

use App\Exceptions\MessageException;
use App\Helpers\Helper;
use App\Models\CmfGgscoreMatchModel;
use App\Models\Sports\SportsFootballMatchModel;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FootballV3Controller extends Controller
{
    /**
     * 查询今明两天的比赛按时间正序排列
     * @OA\Get(
     *      path="/api/v3/football/getMatchAllList",
     *      operationId="getMatchAllList",
     *      tags={"纳米-足球接口"},
     *      summary="比赛列表-全部",
     *      description="根据日期分组",
     *      @OA\Parameter(
     *          name="page",
     *          description="页码",
     *          required=true,
     *          in="path",
     *          example=1,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="List posts",
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="日期",
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/纳米-足球比赛列表")
     *          ),
     *      )
     *   )
     *  ),
     */
    public function getMatchAllList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'required|int'
        ]);

        if ($validator->fails()) return Helper::returnEx(MessageException::CODE_FAIL, '请检查参数！');

        $input = $request->input();
        $input['sport_id'] = 202;
        $matchList = SportsFootballMatchModel::getMatchAllListV3($input);

        return Helper::returnJson($matchList);
    }


    public function getMatchListByHot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'required|int'
        ]);

        if ($validator->fails()) return Helper::returnEx(MessageException::CODE_FAIL, '请检查参数！');

        $input = $request->input();
        $matchList = SportsFootballMatchModel::getMatchListByHot($input);

        return Helper::returnJson($matchList);
    }

    /**
     * @OA\Get(
     *      path="/api/v3/football/getMatchPlayingList",
     *      operationId="getMatchPlayingList",
     *      tags={"纳米-足球接口"},
     *      summary="比赛列表-进行中",
     *      description="根据日期分组",
     *      @OA\Parameter(
     *          name="page",
     *          description="页码",
     *          required=true,
     *          in="path",
     *          example=1,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="List posts",
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="日期",
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/纳米-足球比赛列表")
     *          ),
     *      )
     *   )
     *  ),
     */
    public function getMatchPlayingList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'required|int'
        ]);

        if ($validator->fails()) return Helper::returnEx(MessageException::CODE_FAIL, '请检查参数！');

        $input = $request->input();
        $matchList = SportsFootballMatchModel::getMatchPLayingListV3($input);

        return Helper::returnJson($matchList);
    }

    /**
     * @OA\Get(
     *      path="/api/v3/football/getMatchListByDate",
     *      operationId="getMatchListByDate",
     *      tags={"纳米-足球接口"},
     *      summary="比赛列表-根据日期获取",
     *      description="根据日期获取",
     *      @OA\Parameter(
     *          name="page",
     *          description="页码",
     *          required=true,
     *          in="path",
     *          example=1,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="date",
     *          description="日期",
     *          required=true,
     *          in="path",
     *          example="2020-10-30",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="action",
     *          description="1赛程，2赛果",
     *          required=false,
     *          in="path",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="List posts",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="数组",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/纳米-足球比赛列表")
     *              ),
     *          )
     *      )
     *  ),
     */
    public function getMatchListByDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'required|int',
            'date' => 'required|string',
            'action' => ['nullable', 'int', Rule::in([1, 2])]
        ]);

        if ($validator->fails()) return Helper::returnEx(MessageException::CODE_FAIL, '请检查参数！');

        $input = $request->input();
        if (empty($input['action']))$input['action'] = 0;
        $matchList = SportsFootballMatchModel::getMatchListByDateV3($input);

        return Helper::returnJson($matchList);
    }


    /**
     * @OA\Get(
     *      path="/api/v2/football/getMatch",
     *      operationId="getMatch",
     *      tags={"纳米-足球接口"},
     *      summary="比赛详情",
     *      description="根据比赛id获取",
     *      @OA\Parameter(
     *          name="matchId",
     *          description="比赛id",
     *          required=true,
     *          in="path",
     *          example=74445,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="List posts",
     *          @OA\JsonContent(ref="#/components/schemas/纳米-足球比赛列表")
     *      )
     *  ),
     */
    public function getMatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'matchId' => 'required|int',
        ]);

        if ($validator->fails()) return Helper::returnEx(MessageException::CODE_FAIL, '请检查参数！');

        $input = $request->input();
        $matchList = SportsFootballMatchModel::getMatch($input);

        return Helper::returnJson($matchList);
    }



    public function getMatchSum(Request $request)
    {
        $matchList = SportsFootballMatchModel::getMatchSum();

        return Helper::returnJson($matchList);
    }
}
