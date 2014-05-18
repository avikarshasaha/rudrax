<?php
/*
 * To change this license header, choose License Headers in Project Properties. To change this template file, choose Tools | Templates and open the template in the editor.
*/
require_once ("../config.php");
require_once(RUDRA."/rudrax.php");


RudraX::includePages();

global $RDb;
$RDb = RudraX::getDB('DB1');


global $user;
$user = new User();

$handler = "index";
if (isset($_GET ['t'] ))
	$handler = $_GET ['t'];

global $controller;
$controller = new PageController();
$controller->invoke($user, $handler );

$RDb->close();
	