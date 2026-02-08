<?php

namespace App\Http\Controllers;

use App\Exceptions\MessageException;
use App\Helpers\Helper;
use App\Models\Sports\SportsDota2MatchModel;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Dota2Controller extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/dota2/getMatchAllList",
     *      operationId="getMatchAllList",
     *      tags={"dota2接口"},
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
     *              @OA\Items(ref="#/components/schemas/dota2比赛列表")
     *          ),
     *      )
     *   )
     *  ),
     */
    public function getMatchAllList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'required|int',
            'action' => ['required', 'string', Rule::in(['all', 'playing', 'schedule', 'result'])]

        ]);

        if ($validator->fails()) return Helper::returnEx(MessageException::CODE_FAIL, '请检查参数！');

        $input = $request->input();

        return SportsDota2MatchModel::getMatchList($input);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/dota2/getMatchPlayingList",
     *      operationId="getMatchPlayingList",
     *      tags={"dota2接口"},
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
     *              @OA\Items(ref="#/components/schemas/dota2比赛列表")
     *          ),
     *      )
     *   )
     *  ),
     */
    public function getMatchPlayingList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'required|int',
            'action' => ['required', 'string', Rule::in(['all', 'playing', 'schedule', 'result'])]
        ]);

        if ($validator->fails()) return Helper::returnEx(MessageException::CODE_FAIL, '请检查参数！');

        $input = $request->input();

        return SportsDota2MatchModel::getMatchList($input);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/dota2/getMatchScheduleList",
     *      operationId="getMatchScheduleList",
     *      tags={"dota2接口"},
     *      summary="比赛列表-赛程",
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
     *              @OA\Items(ref="#/components/schemas/dota2比赛列表")
     *          ),
     *      )
     *   )
     *  ),
     */
    public function getMatchScheduleList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'required|int',
            'action' => ['required', 'string', Rule::in(['all', 'playing', 'schedule', 'result'])],
            'date' => 'required|string',
        ]);

        if ($validator->fails()) return Helper::returnEx(MessageException::CODE_FAIL, '请检查参数！');

        $input = $request->input();

        return SportsDota2MatchModel::getMatchList($input);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/dota2/getMatchResultList",
     *      operationId="getMatchResultList",
     *      tags={"dota2接口"},
     *      summary="比赛列表-赛果",
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
     *              @OA\Items(ref="#/components/schemas/dota2比赛列表")
     *          ),
     *      )
     *   )
     *  ),
     */
    public function getMatchResultList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'required|int',
            'action' => ['required', 'string', Rule::in(['all', 'playing', 'schedule', 'result'])],
            'date' => 'required|string',
        ]);

        if ($validator->fails()) return Helper::returnEx(MessageException::CODE_FAIL, '请检查参数！');

        $input = $request->input();

        return SportsDota2MatchModel::getMatchList($input);
    }



}
