<?php

/*
 * To change this license header, choose License Headers in Project Properties. To change this template file, choose Tools | Templates and open the template in the editor.
*/

include_once (RUDRA . "/model/AbstractUser.php");

abstract class AbstractController {

	public static function preRequest(AbstractUser $user, $handlerName) {
		return true;
	}

	public static function postRequest(AbstractUser $user, $handlerName) {
		return true;
	}

	public function invoke(AbstractUser $user, $handlerName) {
		if ($this->preRequest($user, $handlerName )) {
			$this->invokeHandler($user, $handlerName );
			$this->postRequest($user, $handlerName );
		}
	}

	abstract public function invokeHandler(AbstractUser $user, $handlerName);

}
