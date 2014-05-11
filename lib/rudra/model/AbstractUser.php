<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Lalit Tanwar
 */
class AbstractUser {

    public $valid;
    public $uname;
    public $uid;

    public function validate() {
        if (isset($_SESSION['uid']) && trim($_SESSION['uid'])) {
            $this->valid = TRUE;
            $this->uid = $_SESSION['uid'];
        } else {
            $this->valid = FALSE;
            $this->uname = "Guest";
            $this->uid = -1;
        }
    }

    public function getUserName() {
        return $this->uname;
    }

    public function setValid() {
        $this->valid = TRUE;
        session_regenerate_id();
        $_SESSION['uid'] = $this->uid;
        $_SESSION['uname'] = $this->uname;
        session_write_close();
    }

    public function setInValid() {
        $this->valid = TRUE;
        session_destroy();
    }

    public function auth($username, $passowrd) {
        if (strcmp($username, "admin") == 0) {
            setValid();
        }
    }
    public function unauth() {
         $this->setInValid();
    }
    public function isValid() {
        return $this->valid;
    }

}
