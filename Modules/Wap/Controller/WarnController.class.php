<?php
namespace Home\Controller;


class WarnController extends HomeBaseController {


	public function _initialize(){
        parent::_initialize();
	}


    public function index(){

        $this->display('index');
    }
}