<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2019/9/28
 * Time: 16:47
 */

namespace App\HttpController\Api;

use App\Utility\ClassArr;
use EasySwoole\Http\Message\Status;

class Upload extends Base
{
    public function uploadVideo()
    {
        $request = $this->request();
        $files = $request->getSwooleRequest()->files;
        $types = array_keys($files);
        $type = $types[0];
        if (empty($type)) {
            return $this->writeJson(Status::CODE_BAD_REQUEST, '上传文件不合法');
        }

        // PHP 反射机制
        try {
            $classObj = new ClassArr();
            $classStats = $classObj->uploadClassStat();
            $uploadObj = $classObj->initClass($type, $classStats, [$request, $type]);
            $file = $uploadObj->upload();
        } catch (\Exception $e) {
            return $this->writeJson(Status::CODE_BAD_REQUEST, $e->getMessage(), []);
        }
        if (empty($file)) {
            return $this->writeJson(Status::CODE_BAD_REQUEST, "上传失败", []);
        }

        $data = [
            'url' => $file,
        ];
        return $this->writeJson(Status::CODE_OK, "OK", $data);
    }
}
