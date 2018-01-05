class NoteSharedComponent extends Fronty.ModelComponent {
  constructor(notesModel, userModel, router) {
    super(Handlebars.templates.notesharedtable, notesModel, null, null);
    this.notesModel = notesModel;
    this.userModel = userModel;
    this.addModel('user', userModel);
    this.router = router;

    this.notesService = new NotesService();

	this.addEventListener('click', '.remove-button', (event) => {
      if (confirm(I18n.translate('Are you sure?'))) {
        var IdNota = event.target.getAttribute('item'); alert(IdNota);
        this.notesService.deleteNote(IdNota)
          .fail(() => {
            alert('note cannot be deleted')
          })
          .always(() => {
            this.updateNotes();
          });
      }
    });

	this.userModel.addObserver(() => {
		if (this.userModel.isLogged) {
			this.updateNotes();
		}
	});

  }

  onStart() {
	  if (this.userModel.isLogged) {
		this.updateNotes();
	  }
  }

  updateNotes() {
	  this.notesService.findNoteShared().then((data) => {
		this.notesModel.setNotes(
        // create a Fronty.Model for each item retrieved from the backend
        data.map(
          (item) => new NoteModel(item.IdNota, item.nombre, item.contenido, item.autor)
      ));
    });
  }



}
