<?php

/*
 * RudraX Framework : https://github.com/lnt/rudrax
*/

require_once ("../config.php");
define("IDLE_TIME", 3); //3 seconds
define("IDLE_WAIT", 10);
/**
 *
 * @abstract - data-request
 *
 * @author <a href="mailto:lalit.tanwar07@gmail.com">Lalit Tanwar</a>
 * @version 1.3
*/

if (file_exists(get_include_path() . CONTROLLER_PATH . "/NotificationController.php" )) {
	include_once (CONTROLLER_PATH . "/NotificationController.php");
} else {
	include_once (RUDRA . "/controller/NotificationController.php");
}

global $TunnelDB;
$TunnelDB = DBUtils::getDB('TUNNEL' );

function invokeController() {
	global $controller;
	global $user;
	$user = new User();
	$token = $_REQUEST ['token'];
	$handler = 'handshake';
	if (isset($_REQUEST ["@"] )) {
		$handler = $_REQUEST ["@"];
	}

	$controller = new NotificationController();
	$controller->setToken($token);
	$controller->invoke($user, $handler );
}
invokeController();
$TunnelDB->close();



