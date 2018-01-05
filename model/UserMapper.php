<?php


require_once(__DIR__."/../core/PDOConnection.php");


class UserMapper {


	private $db;

	public function __construct() {
		$this->db = PDOConnection::getInstance();
	}


	public function save($user) {
		$stmt = $this->db->prepare("INSERT INTO usuario values (?,?,?)");
		$stmt->execute(array($user->getLogin(), $user->getPassword(), $user->getEmail()));
	}


	public function usernameExists($login) {
		$stmt = $this->db->prepare("SELECT count(login) FROM usuario where login=?");
		$stmt->execute(array($login));

		if ($stmt->fetchColumn() > 0) {
			return true;
		}
	}


	public function isValidUser($login, $password) {
		$stmt = $this->db->prepare("SELECT count(login) FROM usuario where login=? and password=?");
		$stmt->execute(array($login, $password));

		if ($stmt->fetchColumn() > 0) {
			return true;
		}
	}
}
