<?php

namespace App\Utility\Upload;

class Video extends Base
{
    /**
     * fileType
     * @var string
     */
    public $fileType = "video";

    public $maxSize = 122;

    /**
     * 文件后缀
     * @var [type]
     */
    public $fileExtTypes = [
        'mp4',
        'x-flv',
        // todo
    ];
}