<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

class Sampleone extends AbstractHandler {

	public function invokeHandler($tpl) {
		if (isset($_GET['param'])) {
			$tpl->assign('param', $_GET['param']);
			return 'sample/withparam';
		} else {
			return 'sample/noparam';
		}
	}

}
