<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

class Topbar extends AbstractHandler {

	public function invokeHandler(Smarty $tpl) {
		if ($this->user->isValid()) {
			$tpl->assign('profile', $this->user->getProfile());
			return 'topbar';
		} else {
			return 'empty';
		}
	}

}
