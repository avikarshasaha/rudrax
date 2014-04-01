<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Lalit
 */
class AbstractUser {

    public $valid;
    public $uname;

    public function validate() {
        if (isset($_SESSION['user']) && trim($_SESSION['user'])) {
            $this->valid = TRUE;
            $this->uname = $_SESSION['user'];
        } else {
            $this->valid = FALSE;
            $this->uname = "Guest";
        }
    }

    public function getUserName() {
        return $this->uname;
    }

    public function setValid() {
        $this->valid = TRUE;
        session_regenerate_id();
        $_SESSION['user'] = $this->uname;
        session_write_close();
    }

    public function setInValid() {
        $this->valid = TRUE;
        session_destroy();
    }

    public function auth($username, $passowrd) {
        if (strcmp($username, "admin") == 0) {
            //debug("workng");
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
