<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tunnel
 *
 * @author Lalit
 */
define("BASE_PATH", dirname(__FILE__) );
define("PROJECT_PATH", realpath(BASE_PATH . "/../"));
define("R_PATH",PROJECT_PATH."/lib/rudra/");
require_once R_PATH. "AbstractDb.php";
require_once R_PATH . "/AbstractUserNotification.php";

$GLOBALS['CONFIG'] = parse_ini_file("../rudrax.ini",true);

$type = 'longpolling';
if(isset($_REQUEST["type"])){
    $type =     $_REQUEST["type"];
}
include_once ("/tunnel/".$type.".php");