<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once(R_PATH . "/AbstractUser.php");

/**
 * Description of User, it basically extends AbstractUser and implemetns atleast two methods
 *
 * @author Lalit
 */
class User extends AbstractUser {

    public function auth($username, $passowrd) {
        if (strcmp($username, "admin") == 0) {
            //debug("workng");
            $this->uname = $username;
            $this->setValid();
        }
    }

    public function getProfile() {
        return array('fname' => 'John',
            'lname' => 'Smith',
            'email' => 'john.smith@example.com'
        );
    }

    public function unauth() {
        $this->setInValid();
    }

}
