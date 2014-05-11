<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once(RUDRA . "/model/AbstractUser.php");

/**
 * Description of User, it basically extends AbstractUser and implemetns atleast two methods
 *
 * @author Lalit Tanwar
 */
class User extends AbstractUser {
	
	public function getToken(){
		return $this->uid;
	}
	
    public function auth($username, $passowrd) {
    	global $RDb;
    	$res =  $RDb->fetchAll(
				"SELECT * FROM user WHERE username = '%s' AND password = '%s'", 
						$username,$passowrd);
		if(count($res)==0){
			return false;
		} else {
			$this->uname = $username;
			$this->uid  = $res[0]->id;
			$this->setValid();
			return true;
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
