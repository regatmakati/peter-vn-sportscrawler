<?php

namespace App\Http\Controllers;


use App\Exceptions\MessageException;
use App\Helpers\Helper;
use App\Models\CmfSportsBasketballMatchModel;
use App\Models\CmfStatisticsModel;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StatisticsController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/statistics/click",
     *      operationId="statistics.click",
     *      tags={"统计接口"},
     *      summary="点击统计",
     *      description="",
     *      @OA\Parameter(
     *          name="type",
     *          description="类型：1、app下载",
     *          required=true,
     *          in="path",
     *          example=1,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="action",
     *          description="操作：1、PC端android，2、PC端ios，3、h5端android，4、h5端ios",
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
     *                  property="code",
     *                  type="integer",
     *                  example=200
     *              ),
     *              @OA\Property(
     *                  property="msg",
     *                  type="string",
     *                  example="请求成功！"
     *              )
     *          )
     *      )
     *   )
     *  ),
     */
    public function click(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|int',
            'action' => 'required|int'
        ]);

        if ($validator->fails()) return Helper::returnEx(MessageException::CODE_FAIL, '请检查参数！');

        $input = $request->input();

        CmfStatisticsModel::click($input);

        return Helper::returnSuccess();
    }

}
