<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/* Add on 04-23-2014 By Avikarsha*/

ini_set ( 'display_errors', 1 );
ini_set ( 'error_reporting', E_ALL );
ob_start ();
session_start ();

$GLOBALS ['CONFIG'] = parse_ini_file ( "config.ini", true );
set_include_path ($GLOBALS['CONFIG']['WORK_DIR']);
define ( 'APP_PATH', $GLOBALS ['CONFIG']['APP_PATH']);
define ( 'LIB_PATH', $GLOBALS ['CONFIG']['LIB_PATH'] );
define ( 'RUDRA', $GLOBALS ['CONFIG']['RUDRA'] );
define ( 'VIEW_PATH', $GLOBALS ['CONFIG']['VIEW_PATH']);
define ( 'HANDLER_PATH', $GLOBALS ['CONFIG']['HANDLER_PATH'] );
define ( 'DEBUG_ENABLED', $GLOBALS ['CONFIG']['DEBUG_ENABLED'] );
define ( 'MODEL_PATH', $GLOBALS ['CONFIG']['MODEL_PATH'] );
define ( 'CONTROLLER_PATH', $GLOBALS ['CONFIG']['CONTROLLER_PATH'] );

include_once (RUDRA . "/AbstractDb.php");

class DBUtils {
	public static function getDB($configname){
		return new AbstractDb($GLOBALS['CONFIG'][$configname]);
	}
}
if (file_exists ( get_include_path () . MODEL_PATH . "/User.php" )) {
	include_once (MODEL_PATH . "/User.php");
} else {
	include_once (RUDRA . "/_model_User.php");
}
if (file_exists ( get_include_path () . CONTROLLER_PATH . "/TemplateController.php" )) {
	include_once (CONTROLLER_PATH . "/TemplateController.php");
} else {
	include_once (RUDRA . "/_controller_TemplateController.php");
}


require_once RUDRA . "/Console.php";





?>