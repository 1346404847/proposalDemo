<?php
namespace console\controllers;

use yii\console\Controller;
use yii;
use admin\api\Api;
class AssetsController extends Controller
{
    public  function actionIndex()
    {
        $this->getGames();
        $this->getPlatforms();
    }
    /**
     * 获取游戏
     */
    public function getGames()
    {
        $params = ['page_size'=>1000, 'type'=>1, 'status'=>[0,1,2]];
        $method = 'getCategories';
        $gameArr = Api::getAssets($params, $method);

        if(isset($gameArr['flag']) && $gameArr['flag'] == 1) {
            foreach($gameArr['data'] as $key => $value) {
                $k = 'game.'.$value['id'];
                //缓存
                Yii::$app->cache->set($k, $value);
            }
            Yii::$app->cache->set('game.0',$gameArr['data']);
        }
        
        echo date('Y-m-d H:i:s',time())."游戏缓存完成\r\n";
    
    }
    
    /**
     * 获取平台/区组
     *
     * @return mixed
     */
    public function getPlatforms ()
    {
        $games = Yii::$app->cache->get('game.0');
        if(empty($games)) {
            die('获取游戏失败');
        }
        foreach ($games as $key =>$value) {
            $key = 'platform.' . $value['prefix'];
            $params = ['page_size' => 1000,'main_category_id' => $value['id'],'status'=>['0','1']];
            $method = 'getPlatforms';
            $platformArr = Api::getAssets($params, $method);
            $value = [];

            if ($platformArr['flag'] == 1) {
                $value = $platformArr['data'];
                //缓存平台
                Yii::$app->cache->set($key, $value);
                //缓存区组
                foreach ($value as $platform) {
                    $this->getGroups($platform);
                }
            } else {
                echo '获取平台失败：'.$games['prefix'];
            }
        }

        echo date('Y-m-d H:i:s', time())." 平台/区组缓存完成\r\n";
    }
    
    /**
     * 获取区组
     *
     * @param $platform 平台信息
     * @return bool
     */
    public function getGroups ($platform)
    {
        $key = 'group.' . $platform['id'];
        $params['page_size'] = 1000;
        $params['platform_id'] = $platform['id'];
        $method = 'getGroups';
    
        $res = Api::getAssets($params, $method);

        if ($res['flag'] == 1) {
            Yii::$app->cache->set($key, $res['data']);
        } else {
           echo '获取分区失败: '. $platform['prefix'];
        }
    
        return true;
    }
}

?>