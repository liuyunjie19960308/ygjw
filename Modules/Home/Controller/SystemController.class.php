<?php
namespace Home\Controller;

/**
 * Class SystemController
 * @package Home\Controller
 * 系统控制器123
 */
class SystemController extends HomeBaseController {

    function error404() {
        $this->display('error404');
    }

    function payError() {
        $this->display('payError');
    }

    function paySuccess() {
        $this->display('paySuccess');
    }

    function download() {
        $this->display('download');
    }
}