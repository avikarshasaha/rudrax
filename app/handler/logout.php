<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/
/**
 * Description of LoginSubmit
 *
 * @author Lalit
 */
class Logout extends AbstractHandler {

	public function invokeHandler($tpl) {
		$this->user->unauth();
		return 'login';
	}

}
