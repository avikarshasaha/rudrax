<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

class Index extends AbstractHandler {

	public function invokeHandler($viewModel,$header) {
		$header->import('bootstrap');


		if (isset($_REQUEST['uname'])) {
			$username = $_POST['uname'];
			$password = $_POST['pass'];
			$this->user->auth($username, $password);
		}

		//Console::log($this->user->getToken(),$username,$password);
		if ($this->user->isValid()) {
			$viewModel->assign('token',$this->user->getToken());

			$viewModel->assign('profile', $this->user->getProfile());

			$viewModel->assign("Name", "Fred Irving Johnathan Bradley Peppergill", true);
			$viewModel->assign("FirstName", array("John", "Mary", "James", "Henry"));
			$viewModel->assign("LastName", array("Doe", "Smith", "Johnson", "Case"));
			$viewModel->assign("Class", array(array("A", "B", "C", "D"), array("E", "F", "G", "H"),
					array("I", "J", "K", "L"), array("M", "N", "O", "P")));

			$viewModel->assign("contacts", array(array("phone" => "1", "fax" => "2", "cell" => "3"),
					array("phone" => "555-4444", "fax" => "555-3333", "cell" => "760-1234")));

			$viewModel->assign("option_values", array("NY", "NE", "KS", "IA", "OK", "TX"));
			$viewModel->assign("option_output", array("New York", "Nebraska", "Kansas", "Iowa", "Oklahoma", "Texas"));
			$viewModel->assign("option_selected", "NE");
			return "home/home";
		} else {
			return "login";
		}
	}

}
