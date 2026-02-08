<?php

namespace App\Http\Controllers;


use App\Exceptions\MessageException;
use App\Helpers\Helper;
use App\Models\CmfLiveModel;
use App\Models\CmfSportsBasketballMatchModel;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LiveController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/live/getRandomList",
     *      operationId="getRandomList",
     *      tags={"体育直播相关接口"},
     *      summary="直播间列表-随机",
     *      description="",
     *      @OA\Parameter(
     *          name="limit",
     *          description="取多少数据，limit默认4，最大为10",
     *          required=false,
     *          in="path",
     *          example=4,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="class_id",
     *          description="不传此参数显示全部分类的随机直播，直播间分类id：2篮球，3英雄联盟，4足球，5星秀，6其他",
     *          required=false,
     *          in="path",
     *          example=4,
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
     *                  @OA\Items(ref="#/components/schemas/直播间列表")
     *              ),
     *          )
     *      )
     *  ),
     */
    public function getRandomList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'nullable|int|max:10',
            'class_id' => 'nullable|int',
        ]);

        if ($validator->fails()) return Helper::returnEx(MessageException::CODE_FAIL, '请检查参数！');

        $input = $request->input();
        if (empty($input['limit'])) $input['limit'] = 4;
        $liveList = CmfLiveModel::getRandomList($input);

        return Helper::returnJson($liveList);
    }


    /**
     * @OA\Get(
     *      path="/api/v1/live/getPcLiveList",
     *      operationId="getPcLiveList",
     *      tags={"体育直播相关接口"},
     *      summary="直播间列表",
     *      description="",
     *      @OA\Parameter(
     *          name="class_id",
     *          description="不传此参数显示全部分类的直播，直播间分类id：2篮球，3英雄联盟，4足球，5星秀，6其他",
     *          required=false,
     *          in="path",
     *          example=4,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
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
     *          name="limit",
     *          description="取多少数据，limit默认12，最大为20",
     *          required=false,
     *          in="path",
     *          example=12,
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
     *                  @OA\Items(ref="#/components/schemas/直播间列表")
     *              ),
     *          )
     *      )
     *  ),
     */
    public function getPcLiveList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'required|int',
            'class_id' => 'nullable|int',
            'limit' => 'nullable|int|max:20',
        ]);

        if ($validator->fails()) return Helper::returnEx(MessageException::CODE_FAIL, '请检查参数！');

        $input = $request->input();
        if (empty($input['limit'])) $input['limit'] = 12;
        if (empty($input['class_id'])) $input['class_id'] = 0;
        $liveList = CmfLiveModel::getPcLiveList($input);
        return $liveList;
    }
}
