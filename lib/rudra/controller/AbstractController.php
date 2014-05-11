<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once(RUDRA."/model/AbstractUser.php");

class AbstractController {
	
	public static function preRequest($handlerName, AbstractUser $user) {
		return true;
	}
	
	public static function postRequest($handlerName, AbstractUser $user) {
		return true;
	}
	
	public function invoke(AbstractUser $user, $handlerName) {
		if ($this->preRequest ( $handlerName, $user )) {
			$this->invokeHandler ( $handlerName, $user );
			$this->postRequest ( $handlerName, $user );
		}
	}
	
}
