<?php

namespace App\Model\Es;

use EasySwoole\Component\Singleton;
use Elasticsearch\ClientBuilder;

class EsClient
{
    use Singleton;
    public $esClient = null;

    private function __construct()
    {
        $config = \Yaconf::get("es");
        try {
            $this->esClient = ClientBuilder::create()->setHosts([$config['host'] . ":" . $config['port']])->build();
        } catch (\Exception $e) {
            // todo
        }

        if (empty($this->esClient)) {
            // todo
        }
    }

    public function __call($name, $arguments)
    {

        ///var_dump(...$arguments);
        return $this->esClient->$name(...$arguments);
    }
}