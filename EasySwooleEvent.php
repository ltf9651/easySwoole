<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;

use App\Utility\Process\HotReload;
use App\Utility\Process\Consumer;
use EasySwoole\Component\Timer;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use App\Utility\Cache\Video as VideoCache;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
    }

    public static function mainServerCreate(EventRegister $register)
    {
//         开启三个消费进程
        $allNum = 3;
        for ($i = 0; $i < $allNum; $i++) {
            ServerManager::getInstance()->getSwooleServer()->addProcess((new Consumer("consumer_{$i}"))->getProcess());
        }

        // 定时器
        $register->add(EventRegister::onWorkerStart, function (\swoole_server $server, $workerId) {
            if ($workerId == 0) {
                // Timer::loop
                $cacheVideoObj = new VideoCache();
                Timer::getInstance()->loop(5 * 60 * 1000, function () use ($cacheVideoObj) {
                    var_dump('Timer workerId');
                    $cacheVideoObj->setIndexVideo();
                });
            }
        });

        //自动进行服务重载
        $swooleServer = ServerManager::getInstance()->getSwooleServer();
        $swooleServer->addProcess((new HotReload('HotReload', ['disableInotify' => false]))->getProcess());
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}