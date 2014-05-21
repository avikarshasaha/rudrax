<?php
/*
 * To change this license header, choose License Headers in Project Properties. To change this template file, choose Tools | Templates and open the template in the editor.
*/
ini_set('display_errors', 'On');
ini_set ( 'error_reporting', E_ALL );
error_reporting(E_ALL);

require_once("../lib/rudra/rudrax.php");
RudraX::setProjectConfiguration("../project.conf");

global $RDb;
$RDb = RudraX::getDB('DB1');

RudraX::invokeTemplate(function($t="index"){
	global $controller;
	$controller = new TemplateController();
	$controller->invoke($t);
});

$RDb->close();
