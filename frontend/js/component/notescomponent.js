class NotesComponent extends Fronty.ModelComponent {
  constructor(notesModel, userModel, router) {
    super(Handlebars.templates.notestable, notesModel, null, null);


    this.notesModel = notesModel;
    this.userModel = userModel;
    this.addModel('user', userModel);
    this.router = router;

    this.notesService = new NotesService();

  }

  onStart() {
    this.updateNotes();
  }

  updateNotes() {
    this.notesService.findAllNotes().then((data) => {

      this.notesModel.setNotes(
        // create a Fronty.Model for each item retrieved from the backend
        data.map(
          (item) => new NoteModel(item.IdNota, item.nombre, item.contenido, item.autor)
      ));
    });
  }

  // Override
  createChildModelComponent(className, element, id, modelItem) {
    return new NoteRowComponent(modelItem, this.userModel, this.router, this);
  }
}

class NoteRowComponent extends Fronty.ModelComponent {
  constructor(noteModel, userModel, router, notesComponent) {
    super(Handlebars.templates.noterow, noteModel, null, null);

    this.notesComponent = notesComponent;

    this.userModel = userModel;
    this.addModel('user', userModel); // a secondary model

    this.router = router;

    this.addEventListener('click', '.remove-button', (event) => {
      if (confirm(I18n.translate('Are you sure?'))) {
        var IdNota = event.target.getAttribute('item');
        this.notesComponent.notesService.deleteNote(IdNota)
          .fail(() => {
            alert('Note cannot be deleted')
          })
          .always(() => {
            this.notesComponent.updateNotes();
          });
      }
    });

    this.addEventListener('click', '.edit-button', (event) => {
      var IdNota = event.target.getAttribute('item');
      this.router.goToPage('edit-note?IdNota=' + IdNota);
    });
  }

}
