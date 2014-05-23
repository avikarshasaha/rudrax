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

	public function invokeHandler($tpl,$header) {
		$header->import('bootstrap');
		$header->import('utils');
		$header->import('product_login');
		$this->user->unauth();
		return 'sample/login';
	}

}
