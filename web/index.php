<?php
/*
 * To change this license header, choose License Headers in Project Properties. To change this template file, choose Tools | Templates and open the template in the editor.
 */
require_once ("../config.php");

/**
 *
 * @abstract - handles html-template request
 * @author <a href="mailto:lalit.tanwar07@gmail.com">Lalit Tanwar</a>
 * @version 1.3
 */

if (file_exists(get_include_path() . CONTROLLER_PATH . "/TemplateController.php" )) {
	include_once (CONTROLLER_PATH . "/TemplateController.php");
} else {
	include_once (RUDRA . "/controller/TemplateController.php");
}

global $RDb;
$RDb = DBUtils::getDB('DB1' );

function invokeController() {
	global $controller;
	global $user;
	$user = new User();
	
	$temp = "index";
	if (isset($_GET ['t'] ))
		$temp = $_GET ['t'];
	
	$controller = new TemplateController();
	$controller->invoke($user, $temp );
}
invokeController();
$RDb->close();
