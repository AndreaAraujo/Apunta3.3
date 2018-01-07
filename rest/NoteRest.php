<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");

require_once(__DIR__."/../model/Note.php");
require_once(__DIR__."/../model/NoteMapper.php");


require_once(__DIR__."/BaseRest.php");


class NoteRest extends BaseRest {
	private $noteMapper;



	public function __construct() {
		parent::__construct();

		$this->noteMapper = new NoteMapper();

	}


	public function getNotes() {
		$notes = $this->noteMapper->findAll();

		// json_encode Post objects.
		// since Post objects have private fields, the PHP json_encode will not
		// encode them, so we will create an intermediate array using getters and
		// encode it finally
		$notes_array = array();
		foreach($notes as $note) {
			array_push($notes_array, array(
				"IdNota" => $note->getIdNota(),
				"nombre" => $note->getNombre(),
				"contenido" => $note->getContenido(),
				"autor" => $note->getAutor()->getLogin()
			));
		}

		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($notes_array));
	}

	public function createNote($data) {
		$currentUser = parent::authenticateUser();
		$note = new Note();

		if (isset($data->nombre) && isset($data->contenido)) {
			$note->setNombre($data->nombre);
			$note->setContenido($data->contenido);

			$note->setAutor($currentUser);
		}

		try {
			// validate Post object
			$note->checkIsValidForCreate(); // if it fails, ValidationException

			// save the Post object into the database
			$IdNota = $this->noteMapper->save($note);

			// response OK. Also send post in content
			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
			header('Location: '.$_SERVER['REQUEST_URI']."/".$IdNota);
			header('Content-Type: application/json');
			echo(json_encode(array(
				"IdNota"=>$IdNota,
				"nombre"=>$note->getNombre(),
				"contenido" => $note->getContenido()
			)));

		} catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}

	public function readNote($IdNota) {
		// find the Post object in the database
		$note = $this->noteMapper->findById($IdNota);
		if ($note == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Note with id ".$IdNota." not found");
		}

		$note_array = array(
			"IdNota" => $note->getIdNota(),
			"nombre" => $note->getNombre(),
			"contenido" => $note->getContenido(),
			"autor" => $note->getAutor()->getLogin()

		);


		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($note_array));
	}

	public function updateNote($IdNota, $data) {
		$currentUser = parent::authenticateUser();

		$note = $this->noteMapper->findById($IdNota);
		if ($note == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Note with id ".$IdNota." not found");
		}

		// Check if the Post author is the currentUser (in Session)
		if ($note->getAutor() != $currentUser) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("you are not the author of this note");
		}
		$note->setNombre($data->nombre);
		$note->setContenido($data->contenido);

		try {
			// validate Post object
			$note->checkIsValidForUpdate(); // if it fails, ValidationException
			$this->noteMapper->update($note);
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		}catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}

	public function deleteNote($IdNota) {
		$currentUser = parent::authenticateUser();
		$note = $this->noteMapper->findById($IdNota);

		if ($note == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Note with id ".$IdNota." not found");
			return;
		}
		// Check if the Post author is the currentUser (in Session)
		if ($note->getAutor() != $currentUser) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("you are not the author of this note");
			return;
		}

		$this->noteMapper->delete($note);

		header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
	}

	public function shareNote($noteId, $user) {
		$currentUser = parent::authenticateUser();
		$note = $this->noteMapper->findById($noteId);

		if ($note == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Note with id ".$noteId." not found");
		}

		try {
			$this->noteMapper->share($note);

			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');

		}catch(ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}


/*
public function getPostShared() {
	$currentUser = parent::authenticateUser();
	$posts = $this->postMapper->findPostShared($currentUser->getLogin());

	// json_encode Post objects.
	// since Post objects have private fields, the PHP json_encode will not
	// encode them, so we will create an intermediate array using getters and
	// encode it finally
	$posts_array = array();
	foreach($posts as $post) {
		array_push($posts_array, array(
			"IdNota" => $post->getIdNota(),
			"nombre" => $post->getNombre(),
			"contenido" => $post->getContenido(),
			"autor" => $post->getAutor()->getLogin()
		));
	}

	header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
	header('Content-Type: application/json');
	echo(json_encode($posts_array));
}

public function sharePost($IdNota, $user) {
	$currentUser = parent::authenticateUser();
	$post = $this->postMapper->findById($IdNota);

	if ($post == NULL) {
		header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
		echo("Note with id ".$IdNota." not found");
	}

	try {
		$this->postMapper->share($post, $user);

		header($_SERVER['SERVER_PROTOCOL'].' 201 Created');

	}catch(ValidationException $e) {
		header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
		header('Content-Type: application/json');
		echo(json_encode($e->getErrors()));
	}
}

*/

}

// URI-MAPPING for this Rest endpoint
$noteRest = new NoteRest();
/*
URIDispatcher::getInstance()
->map("GET",	"/post", array($postRest,"getPosts"))
->map("GET",	"/post/$1", array($postRest,"readPost"))
->map("POST", "/post", array($postRest,"createPost"))
->map("POST",  "/post/$1/share", array($postRest,"sharePost"))
->map("PUT",	"/post/$1", array($postRest,"updatePost"))
->map("DELETE", "/post/$1", array($postRest,"deletePost"))
->map("GET",	"/shared", array($postRest,"getPostShared"));
*/

URIDispatcher::getInstance()
->map("GET",	"/note", array($noteRest,"getNotes"))
->map("GET",	"/note/$1", array($noteRest,"readNote"))
->map("POST", "/note", array($noteRest,"createNote"))
->map("POST", "/note/$1/share", array($noteRest,"shareNote"))
->map("PUT",	"/note/$1", array($noteRest,"updateNote"))
->map("DELETE", "/note/$1", array($noteRest,"deleteNote"));

?>
