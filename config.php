<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

ob_start ();
session_start ();

ini_set('display_errors', 'On');
ini_set ( 'error_reporting', E_ALL );
error_reporting(E_ALL);


$GLOBALS ['CONFIG'] = parse_ini_file ( "project.conf", true );
set_include_path ($GLOBALS['CONFIG']['WORK_DIR']);
define("BASE_PATH", dirname(__FILE__) );
define("LOG_PATH", $GLOBALS ['CONFIG']['LOG_PATH']);
//define("APP_PATH", realpath(BASE_PATH . "/../../"));
define ( 'APP_PATH', $GLOBALS ['CONFIG']['APP_PATH']);
define ( 'LIB_PATH', $GLOBALS ['CONFIG']['LIB_PATH'] );
define ( 'RUDRA', $GLOBALS ['CONFIG']['RUDRA'] );
define ( 'VIEW_PATH', $GLOBALS ['CONFIG']['VIEW_PATH']);
define ( 'HANDLER_PATH', $GLOBALS ['CONFIG']['HANDLER_PATH'] );
define ( 'DEBUG_ENABLED', $GLOBALS ['CONFIG']['DEBUG_ENABLED'] );
define ( 'MODEL_PATH', $GLOBALS ['CONFIG']['MODEL_PATH'] );
define ( 'CONTROLLER_PATH', $GLOBALS ['CONFIG']['CONTROLLER_PATH'] );

require_once RUDRA . "/Console.php";
Console::set(true);

?>