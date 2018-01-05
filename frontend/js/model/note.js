class NoteModel extends Fronty.Model {

  constructor(IdNota, nombre, contenido, autor) {
    super('NoteModel'); //call super

    if (IdNota) {
      this.IdNota = IdNota;
    }

    if (nombre) {
      this.nombre = nombre;
    }

    if (contenido) {
      this.contenido = contenido;
    }

    if (autor) {
      this.autor = autor;
    }
  }

  setNombre(nombre) {
    this.set((self) => {
      self.nombre = nombre;
    });
  }

  setAutor(autor) {
    this.set((self) => {
      self.Autor = autor;
    });
  }
}
