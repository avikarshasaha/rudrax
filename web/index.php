<?php
/*
 * To change this license header, choose License Headers in Project Properties. To change this template file, choose Tools | Templates and open the template in the editor.
 */
require_once ("../config.php");

include_once (MODEL_PATH . "/User.php");

$RDb = DBUtils::getDB ( 'DB1' );
function invokeController() {
	global $controller;
	global $user;
	$user = new User ();

	$temp = "index";
	if (isset ( $_GET ['t'] ))
		$temp = $_GET ['t'];
	
	$controller = new TemplateController ();
	$controller->invoke ( $user, $temp );
}
invokeController ();
// mysql_close($dbCon);
?>