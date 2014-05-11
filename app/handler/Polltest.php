<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once(RUDRA . "/handler/AbstractHandler.php");

class Polltest extends AbstractHandler {

    public function invokeHandler($tpl) {
        return "polltest/home";
    }

}
