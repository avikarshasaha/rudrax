<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once(RUDRA . "/AbstractHandler.php");

class Tests extends AbstractHandler {

    public function invokeHandler($tpl) {
        return "tests/home";
    }

}
