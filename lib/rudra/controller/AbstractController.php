<?php

/*
 * To change this license header, choose License Headers in Project Properties. To change this template file, choose Tools | Templates and open the template in the editor.
*/

include_once (RUDRA . "/model/AbstractUser.php");

abstract class AbstractController {
	
	public $user;
	
	public function  __construct(){
		$this->user = new User();
	}
	
	public function getHandlerName() {
		return $_GET[PAGE_PARAM];
	}

	public function preRequest(User $user, $handlerName) {
		return true;
	}

	public function postRequest(User $user, $handlerName) {
		return true;
	}

	public function invoke($handlerName) {
		if ($this->preRequest($this->user, $handlerName )) {
			$this->invokeHandler($this->user, $handlerName );
			$this->postRequest($this->user, $handlerName );
		}
	}

	abstract public function invokeHandler(User $user, $handlerName);

}
