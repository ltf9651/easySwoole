<?php

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;

class Index extends Controller
{
    function index()
    {
        //for test
        $res = \Yaconf::get('redis');
        $this->writeJson('1', 'ok', $res);
    }
}
