<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2019/9/29
 * Time: 14:40
 */

namespace App\Model;

use App\Utility\Pool\MysqlPool;
use EasySwoole\Component\Pool\PoolManager;

class Base
{
    public $db;
    public $tableName;

    /**
     * Base constructor.
     * @throws \Throwable
     */
    public function __construct()
    {
        if (empty($this->tableName)) {
            throw new \Exception("table error");
        }

        $db = PoolManager::getInstance()->getPool(MysqlPool::class)->getObj();
        if ($db) {
            $this->db = $db;
        } else {
            throw new \Exception("db error");
        }
    }

    public function add($data)
    {
        if (empty($data) || !is_array($data)) {
            return false;
        }
        return $this->db->insert($this->tableName, $data);
    }
}