class NoteAddComponent extends Fronty.ModelComponent {
  constructor(notesModel, userModel, router) {
    super(Handlebars.templates.noteedit, notesModel);
    this.notesModel = notesModel; // posts

    this.userModel = userModel; // global
    this.addModel('user', userModel);
    this.router = router;

    this.notesService = new NotesService();

    this.addEventListener('click', '#savebutton', () => {
      var newNote = {};
      newNote.nombre = $('#nombre').val();
      newNote.contenido = $('#contenido').val();
      newNote.autor= this.userModel.currentUser;
      this.notesService.addNote(newNote)
        .then(() => {
          this.router.goToPage('notes');
        })
        .fail((xhr, errorThrown, statusText) => {
          if (xhr.status == 400) {
            this.notesModel.set(() => {
              this.notesModel.errors = xhr.responseJSON;
            });
          } else {
            alert('an error has occurred during request: ' + statusText + '.' + xhr.responseText);
          }
        });
    });
  }
  onStart() {
    this.notesModel.setSelectedNote(new NoteModel());
  }
}
