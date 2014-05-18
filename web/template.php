<?php
/*
 * To change this license header, choose License Headers in Project Properties. To change this template file, choose Tools | Templates and open the template in the editor.
*/
require_once ("../config.php");
require_once(RUDRA."/rudrax.php");


RudraX::includeTemplates();

global $RDb;
$RDb = RudraX::getDB('DB1');


global $user;
$user = new User();

$handler = "index";
if (isset($_REQUEST ['t'] ))
	$handler = $_REQUEST ['t'];

global $controller;
$controller = new TemplateController();
$controller->invoke($user, $handler );

$RDb->close();

/* IN_PROGRESS
$NotClass = new ReflectionClass('TemplateController');
$method = $NotClass->getMethod('testMethod');
echo print_r($method->getParameters());

foreach($method->getParameters() as $key => $val){
	$cls = $val->getClass();
	echo '\n<br/>'.$val->getName();
}
*/