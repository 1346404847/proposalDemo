<?php
/**
 * 仅仅是为了生成文档用
 */
namespace admin\doc;

class Doc {

    /**
     * @api {get} /question?uid=:uid 根据uid获取用户提交的问题
     * @apiName GetQuestionList
     * @apiGroup Question
     * @apiVersion 0.1.0
     * @apiHeader {String} Authorization 签名令牌[SERVICE {public_key}:{token}]
     * @apiHeader {String} Date 日期[2015-01-03 12:03:03]
     *
     * @apiParam {String} uid 用户uid
     * @apiParam {String} fields 筛选要返回的数据fields=status,uid
     *
     * @apiExample {curl} curl
     *  curl -X GET
     *      -H "Authorization: SERVICE c8d9b4056:8c1b42360c831a723bf36b7410bff6f2"
     *      -H "Date: 2015-01-03 12:03:03"
     *      -H "Cache-Control: no-cache" -H "Postman-Token: d78c1f4a-e919-0759-be5f-c8d74ac11c2f"
     *      'http://kof.kefu.com/v1/question?uid=14563'
     *
     *  @apiExample {php} php
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://kof.kefu.com/v1/question?uid=14563",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: SERVICE c8d9b4056:8c1b42360c831a723bf36b7410bff6f2",
        "Content-type: multipart/form-data; boundary=---011000010111000001101001",
        "Date: 2015-01-03 12:03:03"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }
     * @apiError {String} message 返回消息
     * @apiError {Number} code 错误码
     * @apiErrorExample {JSON} Error-Response:
        {
          "name": "Forbidden",
          "message": "签名失败5",
          "code": -32002,
          "status": 403
        }
     * @apiSuccess {String} uid 玩家在设置页面看到的账号id.
     * @apiSuccess {String} pid 用户pid.
     * @apiSuccess {String} rid 用户rid.
     * @apiSuccess {String} version 版本.
     * @apiSuccess {String} package_version 客户端安装包的版本号.
     * @apiSuccess {String} game 游戏.
     * @apiSuccess {String} platform 平台.
     * @apiSuccess {String} area_service 服务器.
     * @apiSuccess {String} vip VIP.
     * @apiSuccess {String} role 角色.
     * @apiSuccess {String} op_content 客服回复内容.
     * @apiSuccess {String} op_user 回复人.
     * @apiSuccess {String} op_datetime 回复时间.
     * @apiSuccess {String} title 标题.
     * @apiSuccess {String} content 问题内容.
     * @apiSuccess {Number} status 1: 待处理 2:已处理.
     * @apiSuccessExample {json} Success-Response:
     *   [
     *     {
            "_id": {
              "$id": "55cc3fd47f8b9aed0b8b456e"
            },
            "uid": "14563",
            "pid": "12yasda12asdasd",
            "rid": "70_904",
            "title": "这个为啥不行，你告诉我",
            "version": "微信f",
            "package_version": "111",
            "vip": 11,
            "game": "kof",
            "platform": "IOS",
            "area_service": "904",
            "role": "测试无敌于天下哈哈",
            "content": "哈哈哈",
            "op_content": null,
            "create_time": 1440486453,
            "status": 1,
            "op_user": null,
            "op_datetime": 1440486453
          },
          {
            "_id": {
              "$id": "55cc3f437f8b9aee0b8b456c"
            },
            "uid": "14563",
            "pid": "12yasda12asdasd",
            "rid": "14563_905",
            "title": "这个为啥不行，你告诉我",
            "version": "微信f",
            "package_version": "111"
            "vip": null,
            "game": "kof",
            "platform": "IOS",
            "area_service": "906",
            "role": "测试无敌于天下哈哈",
            "content": "哈哈哈",
            "op_content": null,
            "status": 1,
            "op_user": null,
            "create_time": 1440486453,
            "op_datetime": 1440486453
          }
        ]
     */

    public  function questionGet(){}

    /**
     * @api {post} /question 提交用户反馈
     * @apiName CreateQuestion
     * @apiGroup Question
     * @apiVersion 0.1.0
     * @apiHeader {String} Authorization 签名令牌[SERVICE {public_key}:{token}]
     * @apiHeader {String} Date 日期[2015-01-03 12:03:03]
     *
     * @apiParam {String} uid 玩家在设置页面看到的账号id.
     * @apiParam {String} pid "必填" 用户pid.
     * @apiParam {String} rid 用户rid.
     * @apiParam {String} version "必填" 版本.
     * @apiParam {String} package_version "必填" 客户端安装包的版本.
     * @apiParam {String} game "必填" 游戏.
     * @apiParam {String} platform "必填" 平台.
     * @apiParam {String} area_service "必填" 服务器.
     * @apiParam {String} vip VIP.
     * @apiParam {String} role "必填" 角色.
     * @apiParam {String} op_content 客服回复内容.
     * @apiParam {String} op_user 回复人.
     * @apiParam {String} op_datetime 回复时间.
     * @apiParam {String} title "必填" 标题.
     * @apiParam {String} content "必填" 问题内容.
     * @apiParam {Array} device device[idfa], device[idfa]设备信息
     * @apiParam {Number} status 1: 待处理 2:已处理.
     *
     * @apiError {String} message 返回消息
     * @apiError {Number} code 错误码
     * @apiErrorExample {JSON} Error-Response:
    {
    "name": "Forbidden",
    "message": "签名失败5",
    "code": -32002,
    "status": 403
    }
     * @apiSuccess {String} name ok.
     * @apiSuccess {String} message 提交成功.
     * @apiSuccess {Number} code 200.
     * @apiSuccessExample {json} Success-Response:
        {
          "name": "ok",
          "message": "提交成功",
          "code": "200"
        }
     */
    public function questionCreate(){}

    /**
     * @api {post} /questionnaire 提交用户答卷
     * @apiName CreateQuestionnaire
     * @apiGroup Questionnaire
     * @apiVersion 0.1.0
     * @apiHeader {String} Authorization 签名令牌[SERVICE {public_key}:{token}]
     * @apiHeader {String} Date 日期[2015-01-03 12:03:03]
     *
     * @apiParam {String} uid "必填" 玩家在设置页面看到的账号id.
     * @apiParam {Number} vip "必填" VIP.
     * @apiParam {String} openid "必填" openid.
     * @apiParam {String} role "必填" 角色.
     * @apiParam {String} version "必填" 版本.
     * @apiParam {String} platform "必填" 平台.
     * @apiParam {String} area_service "必填" 服务器.
     * @apiParam {String} level "必填" 等级.
     * @apiParam {String} create_time "必填" 创建时间.
     * @apiParam {String} game "必填" 游戏.
     * @apiParam {JSON} questions "必填" 问卷内容.val值单选、多选为数组，答题为字符串。
     * @apiParamExample {JSON} RequestBody:
     * {
        "uid": "gxpBgfVpzTn",
        "vip": 12,
        "openid": "KuhiuqIz",
        "role": "PXH4QemA",
        "version": "abc",
        "platform": "dev",
        "area_service": "ios",
        "level": 38,
        "create_time": 1481523281,
        "questions": [
            {
                "ID": "4",
                "Id": "3",
                "val": [
                    "Choose3"
                ]
            },
                {
                "ID": "5",
                "Id": "3",
                "val": [
                    "Choose3"
                ]
            },
            {
                "ID": "6",
                "Id": "3",
                "val": [
                    "Choose5"
                ]
            },
            {
                "ID": "8",
                "Id": "4",
                "val": [
                    "Choose1",
                    "Choose2",
                    "Choose3",
                    "Choose4",
                    "Choose5"
                ]
            },
            {
                "ID": "9",
                "Id": "4",
                "val": [
                    "Choose1",
                    "Choose2",
                    "Choose3",
                    "Choose4",
                    "Choose5"
                ]
            },
            {
                "ID": "12",
                "Id": "5",
                "val": "sdfsdfsfxcxcvx"
            }
        ],
        "game": "master"
    }
     *
     * @apiError {String} message 返回消息
     * @apiError {Number} code 错误码
     * @apiErrorExample {JSON} Error-Response:
    {
    "name": "Forbidden",
    "message": "签名失败5",
    "code": -32002,
    "status": 403
    }
     * @apiSuccess {String} name ok.
     * @apiSuccess {String} message 提交成功.
     * @apiSuccess {Number} code 200.
     * @apiSuccessExample {json} Success-Response:
    {
    "name": "ok",
    "message": "提交成功",
    "code": "200"
    }
     */
    public function questionnaireCreate(){}
}