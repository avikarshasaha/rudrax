<?php

/*
 * RudraX Framework : https://github.com/lnt/rudrax
*/

ini_set('display_errors', 'On');
ini_set ( 'error_reporting', E_ALL );
error_reporting(E_ALL);

require_once("../lib/rudra/rudrax.php");
RudraX::setProjectConfiguration("../project.conf");

/**
 *
 * @abstract - data-request
 *
 * @author <a href="mailto:lalit.tanwar07@gmail.com">Lalit Tanwar</a>
 * @version 1.3
*/

global $TunnelDB;
$TunnelDB = RudraX::getDB('TUNNEL');

RudraX::invokeTunnel(function($t="index",$token,$do='handshake'){
	define("IDLE_TIME", 3); //3 seconds
	define("IDLE_WAIT", 10);
	global $controller;
	$controller = new NotificationController();
	$controller->setToken($token);
	$controller->invoke($do);
});

$TunnelDB->close();



