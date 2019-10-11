<?php

namespace App\Utility\Cache;

use App\Model\Video as VideoModel;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Pool\Exception\PoolEmpty;
use EasySwoole\Component\Pool\Exception\PoolException;
use EasySwoole\FastCache\Cache;

class Video
{
    /**
     * 设置缓存数据
     * @throws \Exception
     */
    public function setIndexVideo()
    {
        $catIds = array_keys(\Yaconf::get("category.cats"));
        array_unshift($catIds, 0);
        $cacheType = \Yaconf::get("base.indexCacheType");

        // 写 video json 缓存数据
        foreach ($catIds as $catId) {
            $condition = [];
            if (!empty($catId)) {
                $condition['cat_id'] = $catId;
            }
            try {
                $modelObj = new VideoModel();
                $data = $modelObj->getVideoCacheData($condition);
            } catch (\Exception $e) {
                // 报警 短信 邮件
                $data = [];
            } catch (\Throwable $e) {
            }

            if (empty($data)) {
                continue;
            }

            foreach ($data as &$list) {
                $list['create_time'] = date("Ymd H:i:s", $list['create_time']);
                // 00:01:07  
                $list['video_duration'] = gmstrftime("%H:%M:%S", $list['video_duration']);
            }

            // 通过ini 配置设置存储容器
            switch ($cacheType) {
                case 'file':
                    $res = file_put_contents($this->getVideoCatIdFile($catId), json_encode($data));
                    break;
                case 'table':
                    $res = Cache::getInstance()->set($this->getCatKey($catId), $data);
                    break;
                case 'redis':
                    try {
                        $redis = RedisPool::defer();
                        $res = $redis->set($this->getCatKey($catId), $data);
                    } catch (PoolEmpty $e) {
                    } catch (PoolException $e) {
                    }
                    break;
                default:
                    throw new \Exception("请求不合法");
                    break;
            }

            if (empty($res)) {
                // 记录日志  报警
            }
            // file
            //$flag = file_put_contents(EASYSWOOLE_ROOT."/webroot/video/json/".$catId.".json", json_encode($data));

            // easyswoole cache
//            Cache::getInstance()->set("index_video_data_cat_id_".$catId, $data);
        }

    }

    public function getCache($catId = 0)
    {
        $cacheType = \Yaconf::get("base.indexCacheType");
        switch ($cacheType) {
            case 'file':
                $videoFile = $this->getVideoCatIdFile($catId);
                $videoData = is_file($videoFile) ? file_get_contents($videoFile) : [];
                $videoData = !empty($videoData) ? json_decode($videoData, true) : [];
                break;

            case 'table':
                $videoData = Cache::getInstance()->get($this->getCatKey($catId));
                $videoData = !empty($videoData) ? $videoData : [];
                break;
            case 'redis':
                $redis = RedisPool::defer();
                $videoData = $redis->get("REDIS")->get($this->getCatKey($catId));
                $videoData = !empty($videoData) ? json_decode($videoData, true) : [];
                break;
            default:
                throw new \Exception("请求不合法");
                break;
        }

        return $videoData;
    }

    public function getVideoCatIdFile($catId = 0)
    {
        return EASYSWOOLE_ROOT . "/webroot/video/json/" . $catId . ".json";
    }

    public function getCatKey($catId = 0)
    {
        return "index_video_data_cat_id_" . $catId;
    }
}