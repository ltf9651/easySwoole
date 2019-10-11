<?php

namespace App\HttpController\Api;

use App\Model\Es\EsVideo;
use EasySwoole\Component\Di;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\Component\Pool\PoolManager;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\RedisObject;
use App\Utility\Pool\RedisPool;
use EasySwoole\Http\Message\Status;
use App\Model\Video as VideoModel;
use App\Utility\Cache\Video as VideoCache;

/**
 * Class Index.
 * @package App\HttpController
 */
class Index extends Base
{

    public $params;

    /**
     * 方案 1  - 读取 Mysql
     */
    public function lists_V1()
    {

        $condition = [];
        if (!empty($this->params['cat_id'])) {
            $condition['cat_id'] = intval($this->params['cat_id']);
        }
        //
        // 1 查询 条件 下 count
        // 2 lists
        //

        try {
            $videoModel = new VideoModel();
            $data = $videoModel->getVideoData($condition, $this->params['page'], $this->params['size']);
        } catch (\Exception $e) {
            // $e->getMessage();
            return $this->writeJson(Status::CODE_BAD_REQUEST, "服务异常");
        } catch (\Throwable $e) {
        }

        if (!empty($data['lists'])) {
            foreach ($data['lists'] as &$list) {
                //$data['lists'][$k]['create_time'] = date("Ymd H:i:s", $data['lists'][$k]['create_time']);
                $list['create_time'] = date("Ymd H:i:s", $list['create_time']);
                // 00:01:07
                $list['video_duration'] = gmstrftime("%H:%M:%S", $list['video_duration']);
            }
        }
        return $this->writeJson(Status::CODE_OK, "OK", $data);
    }


    /**
     * 第二套方案 直接读取 静态化 json数据
     */
    public function lists_V2()
    {
        $catId = !empty($this->params['cat_id']) ? intval($this->params['cat_id']) : 0;
        try {
            $videoData = (new VideoCache())->getCache($catId);
        } catch (\Exception $e) {
            return $this->writeJson(Status::CODE_BAD_REQUEST, "请求失败");
        }

        $count = count($videoData);

        return $this->writeJson(Status::CODE_OK, "OK", $this->getPagingDatas($count, $videoData));
    }

    public function getVideo()
    {
        try {
            $db = PoolManager::getInstance()->getPool(MysqlPool::class)->getObj();
            $data = $db->get('test');
            //使用完毕回收
            PoolManager::getInstance()->getPool(MysqlPool::class)->recycleObj($db);
            $this->writeJson(Status::CODE_OK, $data, 'OK');
        } catch (\Throwable $e) {
            //失败处理
        }
    }

    public function getRedis()
    {
        try {
            RedisPool::invoke(function (RedisObject $redis) {
                $redis->set('key', 'test');
                $data = $redis->get('key');
                $this->response()->write($data);
            });
        } catch (PoolEmpty $e) {
        } catch (PoolException $e) {
        } catch (\Throwable $e) {
        }
    }

    public function pub()
    {
        $params = $this->request()->getRequestParam();
        try {
            $redis = RedisPool::defer();
            $redis->rPush('task_list', $params['value']);
        } catch (PoolEmpty $e) {
        } catch (PoolException $e) {
        }
    }

    public function demo()
    {
        $params = [
            "index" => "video",
            "type" => "video",
            //"id" => 1,
            'body' => [

                'query' => [
                    'match' => [
                        'name' => '刘德华'
                    ],
                ],
            ],
        ];

        $client = Di::getInstance()->get("ES");
        $result = $client->search($params);

        return $this->writeJson(200, "OK", $result);
    }

    public function demo2()
    {
        $result = (new EsVideo())->searchByName($this->params['name']);
        return $this->writeJson(200, "OK", $result);
    }
}
