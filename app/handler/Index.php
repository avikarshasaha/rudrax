<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once(R_PATH . "/AbstractHandler.php");

class Index extends AbstractHandler {

    public function invokeHandler($tpl) {
        if (isset($_POST['uname'])) {
            $username = $_POST['uname'];
            $password = $_POST['pass'];
            $this->user->auth($username, $password);
        }
        if ($this->user->isValid()) {
            $tpl->assign('profile', $this->user->getProfile());
            
            $tpl->assign("Name", "Fred Irving Johnathan Bradley Peppergill", true);
            $tpl->assign("FirstName", array("John", "Mary", "James", "Henry"));
            $tpl->assign("LastName", array("Doe", "Smith", "Johnson", "Case"));
            $tpl->assign("Class", array(array("A", "B", "C", "D"), array("E", "F", "G", "H"),
                array("I", "J", "K", "L"), array("M", "N", "O", "P")));

            $tpl->assign("contacts", array(array("phone" => "1", "fax" => "2", "cell" => "3"),
                array("phone" => "555-4444", "fax" => "555-3333", "cell" => "760-1234")));

            $tpl->assign("option_values", array("NY", "NE", "KS", "IA", "OK", "TX"));
            $tpl->assign("option_output", array("New York", "Nebraska", "Kansas", "Iowa", "Oklahoma", "Texas"));
            $tpl->assign("option_selected", "NE");
            return "home";
        } else {
            return "login";
        }
    }

}
