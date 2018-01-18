<?php
// file: model/PostMapper.php
require_once(__DIR__."/../core/PDOConnection.php");

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/Note.php");

class NoteMapper {

	/**
	* Reference to the PDO connection
	* @var PDO
	*/
	private $db;

	public function __construct() {
		$this->db = PDOConnection::getInstance();
	}


	public function findAll() {
		$stmt = $this->db->query("SELECT * FROM nota, usuario WHERE usuario.login = nota.autor");
		$notes_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$notes = array();

		foreach ($notes_db as $note) {
			$author = new User($note["login"]);
			array_push($notes, new Note($note["IdNota"], $note["nombre"], $note["contenido"], $author));
		}

		return $notes;
	}


	public function findById($IdNota){
		$stmt = $this->db->prepare("SELECT * FROM nota WHERE IdNota=?");
		$stmt->execute(array($IdNota));
		$note = $stmt->fetch(PDO::FETCH_ASSOC);

		if($note != null) {
			return new Note(
			$note["IdNota"],
			$note["nombre"],
			$note["contenido"],
			new User($note["autor"]));
		} else {
			return NULL;
		}
	}



		public function save(Note $note) {
			$stmt = $this->db->prepare("INSERT INTO nota(nombre, contenido, autor) values (?,?,?)");
			$stmt->execute(array($note->getNombre(), $note->getContenido(), $note->getAutor()->getLogin()));
			return $this->db->lastInsertId();
		}


		public function update(Note $note) {
			$stmt = $this->db->prepare("UPDATE nota set nombre=?, contenido=? where IdNota=?");
			$stmt->execute(array($note->getNombre(), $note->getContenido(), $note->getIdNota()));
		}



		public function delete(Note $note) {
			$stmt = $this->db->prepare("DELETE from nota WHERE IdNota=?");
			$stmt->execute(array($note->getIdNota()));
		}

		public function share(Nota $nota) {
			$stmt = $this->db->prepare("INSERT INTO notas_compartidas(nomUsu, IdNota) values (?,?)");
			$stmt->execute(array($nota->getNomUsu(), $nota->getIdNota()));
			return $this->db->lastInsertId();
		}


		public function findNoteShared($nombreUsuario) {
			$stmt = $this->db->prepare("SELECT * FROM notas_compartidas, nota ,usuario WHERE notas_compartidas.nomUsu =? and  notas_compartidas.IdNota = nota.IdNota and usuario.login = nota.autor ");
			$stmt->execute(array($nombreUsuario));

			$posts_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$posts = array();

			foreach ($posts_db as $post) {
				$autor = new User($post["login"]);
				array_push($posts, new Note($post["IdNota"], $post["nombre"], $post["contenido"], $autor));
			}

			return $posts;
		}
/*
		public function share(PostShared $post) {
	    $stmt = $this->db->prepare("INSERT INTO notas_compartidas(nomUsu, IdNota) values (?,?)");
	    $stmt->execute(array($post->getNomUsu(), $post->getIdNota()));
			return $this->db->lastInsertId();
	  }


		*/

	}
