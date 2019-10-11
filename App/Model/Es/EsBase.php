<?php

namespace App\Model\Es;

use EasySwoole\Component\Di;

class EsBase
{
    public $esClient = null;
    public $index;
    public $type;

    public function __construct()
    {
        $this->esClient = Di::getInstance()->get("ES");
    }

    public function searchByName($name, $from = 0, $size = 10, $type = "match")
    {
        $name = trim($name);
        if (empty($name)) {
            return [];
        }
        $params = [
            "index" => $this->index,
            "type" => $this->type,
            'body' => [
                'query' => [
                    $type => [
                        'name' => $name
                    ],
                ],
                'from' => $from,
                'size' => $size
            ],
        ];

        $result = $this->esClient->search($params);
        return $result;

    }
}