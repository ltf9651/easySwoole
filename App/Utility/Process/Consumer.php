<?php

namespace App\Utility\Process;

use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\EasySwoole\Logger;

class Consumer extends AbstractProcess
{
    private $isRun = false;

    public function run($arg)
    {
        /*
         * 举例，消费redis中的队列数据
         * 定时500ms检测有没有任务，有的话就while死循环执行
         */
        $this->addTick(500, function () {
            if (!$this->isRun) {
                $this->isRun = true;
                $redis = RedisPool::defer();
                while (true) {
                    try {
                        $task = $redis->lPop('task_list');
                        if ($task) {
                            // do you task
                            // 发送邮件 推送消息 等等 写LOG
                            var_dump($task);
                            Logger::getInstance()->log($this->getProcessName() . "---" . $task);
                        } else {
                            break;
                        }
                    } catch (\Throwable $throwable) {
                        break;
                    }
                }
                $this->isRun = false;
            }
            var_dump($this->getProcessName() . ' task run check');
        });
    }

    public function onShutDown()
    {
        // TODO: Implement onShutDown() method.
    }

    public function onReceive(string $str, ...$args)
    {
        // TODO: Implement onReceive() method.
    }
}