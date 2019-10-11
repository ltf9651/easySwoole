<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2019/9/29
 * Time: 14:40
 */

namespace App\Model;

class Video extends Base
{
    public $tableName = 'video';

    public function getVideoData($condition = [], $page = 1, $size = 10)
    {

        if (!empty($condition['cat_id'])) {
            $this->db->where("cat_id", $condition['cat_id']);
        }
        // 获取正常的内容
        $this->db->where("status", 1);
        if (!empty($size)) {
            $this->db->pageLimit = $size;
        }

        $this->db->orderBy("id", "desc");
        $res = $this->db->paginate($this->tableName, $page);

        $data = [
            'total_page' => $this->db->totalPages,
            'page_size' => $size,
            'count' => intval($this->db->totalCount),
            'lists' => $res,

        ];
        return $data;

    }

    public function getVideoCacheData($condition = [], $size = 1000)
    {
        if (!empty($condition['cat_id'])) {
            $this->db->where("cat_id", $condition['cat_id']);
        }
        // 获取正常的内容
        $this->db->where("status", 1);
        if (!empty($size)) {
            $this->db->pageLimit = $size;
        }

        $this->db->orderBy("id", "desc");
        $res = $this->db->paginate($this->tableName, 1);
        return $res;

    }
}