<?php
/*
 * To change this license header, choose License Headers in Project Properties. To change this template file, choose Tools | Templates and open the template in the editor.
 */
ini_set ( 'display_errors', 1 );
ini_set ( 'error_reporting', E_ALL );
ob_start ();
session_start ();
$GLOBALS ['CONFIG'] = parse_ini_file ( "../rudrax.ini", true );

define ( 'APP_PATH', "app" );
define ( 'LIB_PATH', $GLOBALS ['CONFIG']['LIB_PATH'] );
define ( 'RUDRA', $GLOBALS ['CONFIG']['RUDRA'] );
define ( 'VIEW_PATH', $GLOBALS ['CONFIG']['VIEW_PATH']);
define ( 'HANDLER_PATH', $GLOBALS ['CONFIG']['HANDLER_PATH'] );
define ( 'DEBUG_ENABLED', $GLOBALS ['CONFIG']['DEBUG_ENABLED'] );
set_include_path ( "../" );

require_once RUDRA . "/Console.php";

include_once (RUDRA . "/AbstractDb.php");
include_once (APP_PATH . "/model/User.php");
// $dbCon = connectDb();

$RDb = new AbstractDb ( $GLOBALS ['CONFIG'] ['DB1'] );
function invokeController() {
	$controller;
	global $user;
	$user = new User ();
	$temp = "index";
	if (isset ( $_GET ['t'] ))
		$temp = $_GET ['t'];
	if (file_exists( get_include_path()."app/controller/TemplateController.php")) {
		include_once ("app/controller/TemplateController.php");
		$controller = new TemplateController ();
		// $controller->setName($temp);
		$controller->invoke ( $user, $temp );
	} else {
		throw new Exception("TemplateController.php does not exists. ");
	}
}
function debug($msg) {
	if (DEBUG_ENABLED)
		echo "<script>try{console.log(\"" . $msg . "\");} catch(e){}</script>";
}

invokeController ();
// mysql_close($dbCon);
?>