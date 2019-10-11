<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2019/9/29
 * Time: 14:35
 */

namespace App\HttpController\Api;

use App\Model\Video as VideoModel;
use EasySwoole\Http\Message\Status;

class Video extends Base
{
    public function add()
    {
        $params = $this->request()->getRequestParam();
        try {
            $videoModel = new VideoModel();
            //TODO:: 安全过滤、参数检查、审核等
            $videoId = $videoModel->add($params);
        } catch (\Throwable $e) {
            //TODO:: handle Exception
            return $this->writeJson(Status::CODE_BAD_REQUEST, '提交视频有误', ['id' => 0]);
        }

        if (!empty($videoId)) {
            return $this->writeJson(Status::CODE_OK, 'OK', ['id' => $videoId]);
        } else {
            return $this->writeJson(Status::CODE_BAD_REQUEST, '提交视频有误', ['id' => 0]);
        }
    }
}