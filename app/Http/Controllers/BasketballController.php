<?php

namespace App\Http\Controllers;


use App\Exceptions\MessageException;
use App\Helpers\Helper;
use App\Models\CmfSportsBasketballMatchModel;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BasketballController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/basketball/getMatchAllList",
     *      operationId="getMatchAllList",
     *      tags={"篮球接口"},
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
     *              @OA\Items(ref="#/components/schemas/篮球比赛列表")
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
        $matchList = CmfSportsBasketballMatchModel::getMatchAllList($input);

        return Helper::returnJson($matchList);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/basketball/getMatchPlayingList",
     *      operationId="getMatchPlayingList",
     *      tags={"篮球接口"},
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
     *              @OA\Items(ref="#/components/schemas/篮球比赛列表")
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
        $matchList = CmfSportsBasketballMatchModel::getMatchPLayingList($input);

        return Helper::returnJson($matchList);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/basketball/getMatchListByDate",
     *      operationId="getMatchListByDate",
     *      tags={"篮球接口"},
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
     *                  @OA\Items(ref="#/components/schemas/篮球比赛列表")
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
        $matchList = CmfSportsBasketballMatchModel::getMatchListByDate($input);

        return Helper::returnJson($matchList);
    }


    /**
     * @OA\Get(
     *      path="/api/v1/basketball/getMatch",
     *      operationId="getMatch",
     *      tags={"篮球接口"},
     *      summary="比赛详情",
     *      description="根据比赛id获取",
     *      @OA\Parameter(
     *          name="matchId",
     *          description="比赛id",
     *          required=true,
     *          in="path",
     *          example=10281,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="List posts",
     *          @OA\JsonContent(ref="#/components/schemas/篮球比赛列表")
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
        $matchList = CmfSportsBasketballMatchModel::getMatch($input);

        return Helper::returnJson($matchList);
    }
}
