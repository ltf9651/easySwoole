<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2019/9/28
 * Time: 11:52
 */

namespace App\Utility\Pool;

use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\Mysqli\Config;

class MysqlPool extends AbstractPool
{
    protected function createObject()
    {
        $conf = \EasySwoole\EasySwoole\Config::getInstance()->getConf("MYSQL");
        $dbConf = new Config($conf);
        return new MysqlObject($dbConf);
    }
}