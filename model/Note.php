<?php
// file: model/Post.php

require_once(__DIR__."/../core/ValidationException.php");


class Note {


	private $IdNota;


	private $nombre;


	private $contenido;


	private $autor;



	public function __construct($IdNota=NULL, $nombre=NULL, $contenido=NULL, User $autor=NULL) {
		$this->IdNota = $IdNota;
		$this->nombre = $nombre;
		$this->contenido = $contenido;
		$this->autor = $autor;


	}


	public function getIdNota() {
		return $this->IdNota;
	}

	public function getNombre() {
		return $this->nombre;
	}


	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}


	public function getContenido() {
		return $this->contenido;
	}


	public function setContenido($contenido) {
		$this->contenido = $contenido;
	}



	public function getAutor() {
		return $this->autor;
	}


	public function setAutor(User $autor) {
		$this->autor = $autor;
	}




	public function checkIsValidForCreate() {
		$errors = array();
		if (strlen(trim($this->nombre)) == 0 ) {
			$errors["nombre"] = "Name is mandatory";
		}
		if (strlen(trim($this->contenido)) == 0 ) {
			$errors["contenido"] = "Content is mandatory";
		}


		if (sizeof($errors) > 0){
			throw new ValidationException($errors, "note is not valid");
		}
	}


	public function checkIsValidForUpdate() {
		$errors = array();

		if (!isset($this->IdNota)) {
			$errors["IdNota"] = "IdNota is mandatory";
		}

		try{
			$this->checkIsValidForCreate();
		}catch(ValidationException $ex) {
			foreach ($ex->getErrors() as $key=>$error) {
				$errors[$key] = $error;
			}
		}
		if (sizeof($errors) > 0) {
			throw new ValidationException($errors, "note is not valid");
		}
	}
}
