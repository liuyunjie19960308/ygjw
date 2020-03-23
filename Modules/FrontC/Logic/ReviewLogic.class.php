<?php
namespace FrontC\Logic;

/**
 * 
 */
class ReviewLogic extends FrontBaseLogic
{

    /**
     * @param array $request
     * @return array
     * 获取列表
     */
    public function getList($request = [])
    {
        // 获取数据
        $result = D('FrontC/Review', 'Service')->getList($param, $request);

        return $result;
    }

    /**
     * [getDetail 获取详情]
     * @Author   黑暗中的武者
     * @DateTime 2019-11-10T14:31:36+0800
     * @param    array                    $request [description]
     * @return   [type]                            [description]
     */
    public function getDetail($request = [])
    {
        if (empty($request['review_id'])) {
            return $this->setLogicInfo('参数错误！', false);
        }
        // 查询条件
        $param['where']['review.id'] = $request['review_id'];
        // 查找
        $row = D('FrontC/Review')->findRow($param);
        
        if (!$row) {
            return $this->setLogicInfo('资料不存在！', false);
        }

        //$row['content'] = path2abs($row['content']);
        // 反转义HTML
        $row['content'] = html_entity_decode($row['content']);
        // 获取视频连接 file_url
        if (!empty($row['video_id'])) {
            $video_info = getPlayInfo($row['video_id']);
            $row['video_url'] = $video_info['PlayInfoList']['PlayInfo'][0]['PlayURL'];
        }

        return $row;
    }
}
