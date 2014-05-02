<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



function processExecute(AbstractDb $TunnelDB){
    $token = $_REQUEST['token'];
    $TunnelDB->update("INSERT INTO"
           . " token (`sessionId`, `tokenId`) VALUES ('%s', '%s')",$_COOKIE['PHPSESSID'],$token);
   printf('%s({"time" : "%s", cookie : "%s"});',
           $_REQUEST["callback"], date('d/m H:i:s'),$_COOKIE['PHPSESSID']);
}