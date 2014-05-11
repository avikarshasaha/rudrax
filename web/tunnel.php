<?php

/*
 * RudraX Framework : https://github.com/lnt/rudrax
 * 
 */

/**
 * @description
 * @author <a href="mailto:lalit.tanwar07@gmail.com">Lalit Tanwar</a>
 * @version 1.3
 */
require_once ("../config.php");

global $TunnelDB;
$TunnelDB = DBUtils::getDB ( 'TUNNEL' );

Console::set(true);
$type = 'longpoll';
if(isset($_REQUEST["@"])){
    $type =     $_REQUEST["@"];
}
include_once ("tunnel/".$type.".php");

processExecute($TunnelDB);

