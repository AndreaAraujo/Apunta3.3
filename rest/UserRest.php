<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");
require_once(__DIR__."/BaseRest.php");


class UserRest extends BaseRest {
	private $userMapper;

	public function __construct() {
		parent::__construct();

		$this->userMapper = new UserMapper();
	}

	public function noteUser($data) {
		$user = new User($data->login, $data->password, $data->email);
		try {
			$user->checkIsValidForRegister();

			$this->userMapper->save($user);

			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
			header("Location: ".$_SERVER['REQUEST_URI']."/".$data->login);
		}catch(ValidationException $e) {
			http_response_code(400);
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}

	public function login($login) {
		$currentLogged = parent::authenticateUser();
		if ($currentLogged->getLogin() != $login) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("You are not authorized to login as anyone but you");
		} else {
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
			echo("Hello ".$login);
		}
	}
}

// URI-MAPPING for this Rest endpoint
$userRest = new UserRest();
URIDispatcher::getInstance()
->map("GET",	"/user/$1", array($userRest,"login"))
->map("POST", "/user", array($userRest,"noteUser"));
?>
