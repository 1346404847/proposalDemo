<?php
namespace admin\api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use yii;
use yii\helpers\Json;

class Api{

    /**
     * 接口调用(发送单个请求)
     * @param array $params
     * @param string $url
     * @return json
     */
    public static function sendRequest(array $params,$url)
    {
        if(empty($params) || !is_array($params) || empty($url)) {
            return json_encode(['flag'=>0, 'msg'=>'参数错误']);
        }

        $client = new Client();

        try {
            $response = $client->post($url,$params);
            $body = $response->getBody();
            if($body) {
                
                $rs = $body->getContents();
                return $rs;
            }
        } catch (RequestException $e){
            if($e->hasResponse()) {
                return Json::encode(['flag'=>0, 'msg'=>$e->getMessage()]);
            }
        }
    }
    

    /**
     * 批量发送请求
     * $params = [
     *              ['url'=>'http://*','options'=>['body'=>[]]],
     *              ['url'=>'http://*','options'=>['headers'=>[]]],
     *           ];
     * @param array $params
     * @return array
     */
    public static function sendRequestBath(array $params)
    {
        if(empty($params) || !is_array($params)) {
            return ['flag'=>0, 'msg'=>'参数错误'];
        }
    
        //创建client对象，组织参数
        $client = new Client();
        foreach($params as $key=>$value) {
            if(!isset($value['url'])) {
                continue;
            }
    
            if(isset($value['options'])) {
                $requests[] = $client->createRequest('POST', $value['url'], $value['options']);
            }else{
                $requests[] = $client->createRequest('POST', $value['url']);
            }
        }
    
        if(empty($requests)) {
            return ['flag'=>0, 'msg'=>'参数错误'];
        }
    
        //发送请求
        $results = Pool::batch($client, $requests);
        //返回结果
        try{
            for($i=0; $i<count($results); $i++) {
                if(method_exists($results[$i], 'getBody') && $results[$i]->getBody()) {
                    $rs[$i] = $results[$i]->getBody()->getContents();
                }else{
                    $rs[$i] = [];
                }
            }
            return $rs;
        }catch(RequestException $e) {
            //print_r($e->getRequest());
            if($e->hasResponse()) {
                return ['flag'=>0, 'msg'=>$e->getMessage()];
                //print_r($e->getResponse());
            }
        }
    
    }
    
    
    /**
     * 统一参数配置（资产接口）
     */
    public static function getAssetParams(array $params,$method)
    {
        if(empty($params) || empty($method)) {
           return ['flag'=>0, 'msg'=>'参数错误'];
        }
        $time = date('c');
        $token = self::getAssetoken($params,$time);
        
        $headers = [
            'Content-Type'=>'application/json',
            'Authorization' => 'SERVICE '.Yii::$app->params['apiUid'].':'.$token,
            'Date' => $time,
        ];
        $body = [
            'jsonrpc' => '2.0',
            'id' => time(),
            'method' => $method,
            'params' => $params
        ];
        $data['headers'] = $headers;
        $data['body'] = json_encode($body);
        return ['flag'=>1, 'data'=>$data];
    }
    
    /**
     * 获取签名token（资产接口）
     *
     * @param array $param
     * @param string $secret
     *
     * @return string
     */
    public static function getAssetoken(array $params,$time)
    {
        ksort($params);
        $p = self::convert_array_to_str($params);
        $str = $p.Yii::$app->params['apiSecret'].$time;
        $str = md5($str);
    
        return $str;
    }
     
     
    /**
     * 生成参与签名字符串（资产接口）
     *
     * @param array $params
     * @param $glue
     * @return string
     */
    public static function convert_array_to_str(array $params, $glue='')
    {
        $str = '';
        if ($params && is_array($params)) {
    
            foreach ($params as $key => $value) {
                if (is_array($value)) {
                    sort($value);
                    $tmpStr = implode($glue, $value);
                    $str .= $key . $tmpStr . $glue;
                } else {
                    $str .= $key . $value . $glue;;
                }
            }
        }
        return $str;
    }
    
    /**
     * 获取资产信息
     */
    public static function getAssets(array $params, $method)
    {
        $rs = self::getAssetParams($params, $method);
        if(!$rs['flag']) {
            return $rs;
        }
    
        $data = $rs['data'];
        $url = Yii::$app->params['apiUrl'];
        $resultObj = self::sendRequest($data,$url);
        $resultArr = Json::decode($resultObj);
    
        if(isset($resultArr['result'])) {
            $assets = $resultArr['result'];
            return ['flag'=> 1, 'data'=>  $assets];
        }else{
            return ['flag' => 0, 'msg' => '接口错误或无数据' ];
        }
    }
    
}