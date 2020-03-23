<?php
namespace Common\Base;

/**
 * Class BaseLogic
 * @package Common\Base
 * 集体逻辑层父类
 *          总后台
 *          分后台
 *          Api
 *          Home
 *          Wap
 * 增删 公共方法
 */
class BaseLogic {

    /**
     * @var array
     * 自动验证规则
     */
    protected $rules        = [];

    /**
     * @var string
     * 接收逻辑层提示信息
     */
    protected $logicInfo    = '';

    /**
     * 构造函数
     * @access public
     */
    public function __construct() {
        //初始化执行
        if(method_exists($this, '_initialize'))
            $this->_initialize();
    }

    /**
     * @param array $request
     * @return boolean
     * 修改字段前执行方法
     */
    protected function beforeSetField($request = []) { return true; }

    /**
     * @param int $result
     * @param array $request
     * @return boolean
     * 修改字段成功后执行
     */
    protected function afterSetField($result = 0, $request = []) { return true; }

    /**
     * @param array $request  model 模型  ids操作的主键ID  field要改的字段  value修改为的值
     * @return bool
     * 修改一个字段的值 单一/批量
     */
    public function setField($request = []) {
        //判断参数
        if(empty($request['model']) || empty($request['ids']) || empty($request['field']) || !isset($request['value'])) {
            $this->setLogicInfo('参数错误！'); return false;
        }
        //执行前操作
        if(!$this->beforeSetField($request)) { return false; }
        //判断数组ID 字符ID  获取ID串
        $ids         = $this->getIds($request['ids']);
        //ID条件
        $where['id'] = is_numeric($ids) ? $ids : ['IN', $ids];
        //修改的参数
        $data = [
            $request['field']   => $request['value'],
            'update_time'       => NOW_TIME
        ];
        //修改结果
        $result = D($request['model'])->where($where)->data($data)->save();
        //判断结果
        if($result === false) {
            $this->setLogicInfo('操作失败！'); return false;
        } else {
            //行为日志 总后台端
            if(TERMINAL == 'bms') {
                D('ActionLog', 'Logic')->add('set_field', $request['model'], $ids, AID);
            }
            //执行后操作
            if(!$this->afterSetField($result,$request)) {
                return false;
            }
            //所有删改后执行
            if(!$this->afterAll($result,$request)) {
                return false;
            }
            //返回信息
            $this->setLogicInfo('操作成功！'); return true;
        }
    }

    /**
     * @param array $request
     * @return boolean
     * 彻底删除前执行
     */
    protected function beforeRemove($request = []) { return true; }

    /**
     * @param int $result
     * @param array $request
     * @return boolean
     * 彻底删除成功后执行
     */
    protected function afterRemove($result = 0, $request = []) { return true; }

    /**
     * @param array $request
     * @return bool
     * 彻底删除记录
     */
    public function remove($request = []) {
        //判断参数
        if(empty($request['model']) || empty($request['ids'])) {
            $this->setLogicInfo('参数错误！'); return false;
        }
        //执行前操作
        if(!$this->beforeRemove($request)){ return false; }
        //判断数组ID 字符ID  获取ID串
        $ids         = $this->getIds($request['ids']);
        //ID条件
        $where['id'] = is_numeric($ids) ? $ids : ['IN', $ids];
        //删除结果
        $result      = D($request['model'])->where($where)->delete();
        //判断删除结果
        if($result) {
            //行为日志 总后台端
            if(TERMINAL == 'bms') {
                D('ActionLog', 'Logic')->add('remove', $request['model'], $ids, AID);
            }
            //执行后操作
            if(!$this->afterRemove($result,$request)) {
                return false;
            }
            //所有删改后执行
            if(!$this->afterAll($result,$request)) {
                return false;
            }
            //返回信息
            $this->setLogicInfo('删除成功！'); return true;
        } else {
            $this->setLogicInfo('删除失败！'); return false;
        }
    }

    /**
     * @param $ids
     * @return string
     * 返回ID串
     */
    protected function getIds($ids) {
        //判断数组ID 字符ID
        if(is_array($ids))
            $ids = implode(',', $ids); //数组ID
        return $ids;
    }

    /**
     * @param array $request
     * @return boolean
     * 更新前执行
     */
    protected function beforeUpdate($request = []) { return true; }
    /**
     * @param array $data
     * @param array $request
     * @return array
     * 处理提交数据 进行加工或者添加其他默认数据
     */
    protected function processData($data = [],$request = []) { return $data; }
    /**
     * @param $result
     * @param array $request
     * @return boolean
     * 更新后执行
     */
    protected function afterUpdate($result = 0, $request = []) { return true; }

    protected $add_success_info = '新增成功！';
    protected $upd_success_info = '更新成功！';

    /**
     * @param array $request
     * @param bool $is_return_id
     * @return bool|mixed
     * 新增 或 修改
     */
    public function update($request = [], $is_return_id = false) {
        //执行前操作
        if(!$this->beforeUpdate($request)) {
            return false;
        }
        //数据模型名称
        $model = $request['model']; unset($request['model']);
        //获取数据对象 是否有动态验证规则
        if(empty($this->rules)) {
            $data = D($model)->create($request);
        } else {
            $data = D($model)->validate($this->rules)->create($request);
        }
        //自动验证出错
        if(!$data) {
            $this->setLogicInfo(D($model)->getError()); return false;
        }
        //处理数据
        $data = $this->processData($data, $request);
        //处理数据出错
        if(!$data) { return false; }
        //判断增加还是修改
        if(empty($data['id'])) {
            //新增数据
            $result = D($model)->data($data)->add();
            if(!$result) {
                $this->setLogicInfo('新增时出错！'); return false;
            }
            //行为日志 总后台端
            if(TERMINAL == 'bms') {
                D('ActionLog', 'Logic')->add('add', $model, $result, AID);
            }
        } else {
            //创建修改条件参数
            $where['id'] = $request['id'];
            //更新数据
            $result      = D($model)->where($where)->data($data)->save();
            if($result === false) {
                $this->setLogicInfo('更新时出错！'); return false;
            }
            //行为日志 总后台端
            if(TERMINAL == 'bms') {
                D('ActionLog', 'Logic')->add('edit', $model, $data['id'], AID);
            }
        }
        //执行后操作
        if(!$this->afterUpdate($result,$request)) {
            return false;
        }
        //所有删改后执行
        if(!$this->afterAll($result,$request)) {
            return false;
        }

        //返回信息
        $this->setLogicInfo($data['id'] ? $this->upd_success_info : $this->add_success_info);

		if(!$is_return_id) {
            return true;
        } else {
            return $result;
        }
    }

    /**
     * @param $result
     * @param array $request
     * @return boolean
     * 新增、更新、修改字段、删除后执行
     */
    protected function afterAll($result = 0, $request = []) { return true; }

    /**
     * @param string $info
     * @return string
     * 设置提示信息
     */
    protected function setLogicInfo($info = '') {
        $this->logicInfo = $info;
    }

    /**
     * @return string
     * 获取提示信息
     */
    final public function getLogicInfo() {
        return $this->logicInfo;
    }
}