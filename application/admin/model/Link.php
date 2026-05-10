<?php
namespace app\admin\model;

use think\Model;

class Link extends Model
{
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;

    /**
     * [list 友链列表数据]
     * @param  [type] $page  [description]
     * @param  [type] $limit [description]
     * @return [type]        [description]
     */
    public static function list($page, $limit)
    {
        $result = self::order('id', 'desc')
            ->page($page)
            ->limit($limit)
            ->cache(true, 8640000)
            ->select();

        $count = self::cache(true, 8640000)->count();
        foreach ($result as $key => $value) {
            $value['id_count'] = $count;
            $value['status_text'] = isset($value['status']) && intval($value['status']) === self::STATUS_APPROVED ? '已通过' : '待审核';
            $value['addtime'] = date('Y-m-d H:i:s', $value['addtime'] ?: time());
        }

        return $result;
    }

    public static function links()
    {
        return self::where('status', self::STATUS_APPROVED)->order('id', 'asc')->field('sitename,siteurl')->cache(true, 8640000)->select();
    }
}
