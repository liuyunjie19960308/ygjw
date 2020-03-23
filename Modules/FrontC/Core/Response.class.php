<?php
namespace FrontC\Core;

/**
 * 
 */
class Response
{

	/**
	 * [$Info 提示信息]
	 * @var string
	 */
    private $Info = '';


    /**
     * [setInfo 设置提示信息]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T14:03:50+0800
     * @param    boolean                  $flag [description]
     * @param    string                   $info [description]
     * @param    array                    $data [description]
     */
    public function setInfo($flag = false, $info = '', $data = [])
    {
        $this->Info = $info;
        //如果存在返回数据
        if($flag === true && !empty($data)) {
            return $data;
        }
        return $flag;
    }

    /**
     * [getInfo 获取提示信息]
     * @Author   黑暗中的武者
     * @DateTime 2018-07-24T14:04:00+0800
     * @return   [type]                   [description]
     */
    public function getInfo()
    {
        return $this->Info;
    }
}