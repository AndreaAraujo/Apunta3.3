<?php

class ValidationException extends Exception {

	/**
	* Array of errors
	* @var mixed
	*/
	private $errors = array();

	public function __construct(array $errors, $msg=NULL){
		parent::__construct($msg);
		$this->errors = $errors;
	}

	
	public function getErrors() {
		return $this->errors;
	}
}
