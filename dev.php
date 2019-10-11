<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2019-01-01
 * Time: 20:06
 */

return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER,EASYSWOOLE_REDIS_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
            'reload_async' => true,
            'max_wait_time' => 3
        ],
        'TASK' => [
            'workerNum' => 4,
            'maxRunningNum' => 128,
            'timeout' => 15
        ]
    ],
    //数据库配置
    'MYSQL' => [
        'host' => '127.0.0.1',//数据库连接ip
        'user' => 'root',//数据库用户名
        'password' => '123456',//数据库密码
        'database' => 'video',//数据库
        'port' => '3306',//端口
        'timeout' => '30',//超时时间
        'POOL_MAX_NUM' => '20',
        'POOL_TIME_OUT' => '0.1',
        'connect_timeout' => '5',//连接超时时间
        'charset' => 'utf8',//字符编码
        'strict_type' => false, //开启严格模式，返回的字段将自动转为数字类型
        'fetch_mode' => false,//开启fetch模式, 可与pdo一样使用fetch/fetchAll逐行或获取全部结果集(4.0版本以上)
        'alias' => '',//子查询别名
        'isSubQuery' => false,//是否为子查询
        'max_reconnect_times ' => '3',//最大重连次数
    ],
    //Redis 配置 -> 移动至 Yaconf-directory
//    'REDIS' => [
//        'host'          => '127.0.0.1',
//        'port'          => '6379',
//        'auth'          => '',
//        'db'            => 1,//选择数据库,默认为0
//        'intervalCheckTime'    => 30 * 1000,//定时验证对象是否可用以及保持最小连接的间隔时间
//        'maxIdleTime'          => 15,//最大存活时间,超出则会每$intervalCheckTime/1000秒被释放
//        'maxObjectNum'         => 20,//最大创建数量
//        'minObjectNum'         => 5,//最小创建数量 最小创建数量不能大于等于最大创建
//    ],
    'TEMP_DIR' => null,
    'LOG_DIR' => null,
    //swoole使用CACHE 内存数据落地
    'EASY_CACHE' => [
        'PROCESS_NUM' => 1,//若不希望开启，则设置为0
        'PERSISTENT_TIME' => 0//如果需要定时数据落地，请设置对应的时间周期，单位为秒
    ],
];
