<?php

namespace App\HttpController\Api;

use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Message\Status;

/**
 * Api 基础类库
 * Class Base
 * @package App\HttpController\Api
 */
class Base extends Controller
{
    public $params = [];

    public function index()
    {
    }

    public function onRequest(?string $action): ?bool
    {
        return parent::onRequest($action); // TODO: Change the autogenerated stub
    }

    public function onException(\Throwable $throwable): void
    {
        $this->writeJson(Status::CODE_BAD_REQUEST, '非法请求');
    }

    protected function writeJson($statusCode = 200, $msg = null, $result = null)
    {
        if (!$this->response()->isEndResponse()) {
            $data = Array(
                "code" => $statusCode,
                "msg" => $msg,
                "result" => $result
            );
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus($statusCode);
            return true;
        } else {
            return false;
        }
    }

    //分页
    public function getPagingDatas($count, $data, $isSplice = 1)
    {
        $totalPage = ceil($count / $this->params['size']);
        $maxPageSize = \Yaconf::get("base.maxPageSize");
        if ($totalPage > $maxPageSize) {
            $totalPage = $maxPageSize;
        }
        $data = $data ?? [];

        if ($isSplice == 1) {
            $data = array_splice($data, $this->params['from'], $this->params['size']);
        }

        return [
            'total_page' => $totalPage,
            'page_size' => $this->params['page'],
            'count' => intval($count),
            'lists' => $data,
        ];
    }
}
