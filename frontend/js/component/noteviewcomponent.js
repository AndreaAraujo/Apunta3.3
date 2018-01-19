class NoteViewComponent extends Fronty.ModelComponent {
  constructor(notesModel, userModel, router) {
    super(Handlebars.templates.noteview, notesModel);

    this.notesModel = notesModel; // posts
    this.userModel = userModel; // global
    this.addModel('user', userModel);
    this.router = router;

    this.notesService = new NotesService();

    this.addEventListener('click', '#sharebutton', () => {
	  var user = $('#user').val();
      var selectedId = this.router.getRouteQueryParam('IdNota');

      this.notesService.shareNote(selectedId, user)
       .then(() => {
         this.notesModel.set((model) => {
           model.errors = []
         });
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
    var selectedId = this.router.getRouteQueryParam('IdNota');
    this.loadNote(selectedId);
  }

  loadNote(IdNota) {
    if (IdNota != null) {
      this.notesService.findNote(IdNota)
        .then((note) => {
          this.notesModel.setSelectedNote(note);
        });
    }
  }
}
