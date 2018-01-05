<?php
// file: model/User.php

require_once(__DIR__."/../core/ValidationException.php");


class User {


	private $login;
	private $password;
	private $email;


	public function __construct($login=NULL, $password=NULL, $email = NULL) {

		$this->login = $login;
		$this->password = $password;
    $this->email = $email;
	}


	public function getLogin() {
		return $this->login;
	}

	public function setLogin($login) {
		$this->login = $login;
	}


	public function getPassword() {
		return $this->password;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

  public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}




	public function checkIsValidForRegister() {
		$errors = array();
		if (strlen($this->login) < 5) {
			$errors["login"] = "Username must be at least 5 characters length";

		}
		if (strlen($this->password) < 5) {
			$errors["password"] = "Password must be at least 5 characters length";
		}
		if (strlen($this->email) < 5) {
			$errors["email"] = "Email must be at least 5 characters length";
		}
		if (sizeof($errors)>0){
			throw new ValidationException($errors, "user is not valid");
		}
	}
}
