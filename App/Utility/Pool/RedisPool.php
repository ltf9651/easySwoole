<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2019/9/28
 * Time: 14:13
 */

namespace App\Utility\Pool;

use EasySwoole\Component\Pool\AbstractPool;

class RedisPool extends AbstractPool
{
    protected function createObject()
    {
        $redis = new RedisObject();
        $conf = \Yaconf::get('redis');
        if ($redis->connect($conf['host'], $conf['port'])) {
            if (!empty($conf['auth'])) {
                $redis->auth($conf['auth']);
            }
            return $redis;
        } else {
            return null;
        }
    }
}