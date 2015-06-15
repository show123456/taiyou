<?php

class Helper_MySmarty extends Smarty {

	private $_layout = 'layout.html';
	
	private $_tpl = '';
    
	public function setLayout($name){
		$this->_layout = $name;
		return $this;
	}
	
	public function setTpl($path){
		$this->_tpl = $path;
		$this->assign('_TPL_',$path);
		return $this;
	}
	
	public function display($tpl=null, $cache_id=null, $compile_id=null){
		$layout = $this->_layout ? ROOT_PATH.'/home/public/templates/'.$this->_layout : ROOT_PATH.$this->_tpl;
		parent::display($layout);
	}
}

