<?php

namespace App\Http\Controllers;


use App\Exceptions\MessageException;
use App\Helpers\Helper;
use App\Models\CmfUserModel;
use Dingo\Api\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForKmController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/forKm/loginOrRegister",
     *      operationId="loginOrRegister",
     *      tags={"酷米相关"},
     *      summary="登陆或注册",
     *      description="",
     *      @OA\Parameter(
     *          name="userId",
     *          description="用户id",
     *          required=true,
     *          in="path",
     *          example=4,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="nickName",
     *          description="用户昵称",
     *          required=true,
     *          in="path",
     *          example="游客xxx",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="isLogin",
     *          description="是否登录，true：已登陆，false：未登陆",
     *          required=true,
     *          in="path",
     *          example="false",
     *          @OA\Schema(
     *              type="boolean"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="sign",
     *          description="签名规则：md5(key=xxxx&userId=2180853&nickName=sssssss&isLogin=true)",
     *          required=true,
     *          in="path",
     *          example="1a2c8dee5df11327ce2d15ee57b8e58d",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="List posts",
     *          @OA\JsonContent(ref="#/components/schemas/用户表")
     *      )
     *  ),
     */
    public function loginOrRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|int',
            'nickName' => 'required|string',
            'isLogin' => 'required|string',
            'sign' => 'required|string',
        ]);

        if ($validator->fails()) return Helper::returnEx(MessageException::CODE_FAIL, '请检查参数!');

        $input = $request->input();

        $input['nickName'] = urldecode($input['nickName']);

        $sign = md5("key=16C1FE2D48425D12EAD3D624F3F4633F&userId={$input['userId']}&nickName={$input['nickName']}&isLogin={$input['isLogin']}");

       if ($sign != $input['sign']) return Helper::returnEx(MessageException::CODE_FAIL, '签名校验失败！');

        if (empty($input['nickName']) || $input['nickName'] == '游客') $input['nickName'] = '游客' . rand(1000000, 9999999);

        $user = CmfUserModel::kmLoginOrRegister($input);

        if (empty($user)) return Helper::returnEx(MessageException::CODE_FAIL, '登录失败,请重试！');

        return Helper::returnJson($user);
    }

}
