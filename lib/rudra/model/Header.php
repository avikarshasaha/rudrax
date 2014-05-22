<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

/**
 * Description of User
 *
 * @author Lalit Tanwar
 */
class Header {

	public $scripts = array();
	public $css = array();
	public $dones = array();
	public $config =  array();

	public function  __construct(Smarty $tpl){
		$x = $tpl->configLoad("compose.conf",'*');
		//$tpl->fetch(get_include_path().RUDRA."/view/empty.tpl");
		$this->config = $tpl->getConfigVars();
		//print_r( $this->config); 
	}

	public function import($module){
		if(isset($this->config[$module]) && !isset($dones[$module])){
			$dones[$module] = true;
			$this->add($module,$this->config[$module]);
		}
	}
	
	public function add($module,$list){
		if(isset($list['ON'])){
			$modules = explode(',',$list['ON']);
			foreach($modules as $key=>$module){
				$this->import($module);
			}
		}
		foreach($list as $key=>$value){
			if($key!='ON'){
				$ext = strtolower(pathinfo($value, PATHINFO_EXTENSION));
				if($ext=='js'){
					$this->scripts[$module.".".$key] = $value;
				} else if($ext=='css'){
					$this->css[$module.".".$key] = $value;
				}
			}
		}
	}
	

}
