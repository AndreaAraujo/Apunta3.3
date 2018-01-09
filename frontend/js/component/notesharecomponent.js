class NoteShareComponent extends Fronty.ModelComponent {
  constructor(notesModel, userModel, router) {
    super(Handlebars.templates.noteshare, notesModel);
    this.notesModel = notesModel; // posts
    this.userModel = userModel; // global
    this.addModel('user', userModel);
    this.router = router;

    this.notesService = new NotesService();

    this.addEventListener('click', '.share-button', () => {
      var selectedId = this.router.getRouteQueryParam('IdNota');
      this.notesService.shareNote(selectedId, {
          content: $('#sharecontent').val()
        })
        .then(() => {
          $('.share-button').val('');
          this.loadNote(selectedId);
        })
        .fail((xhr, errorThrown, statusText) => {
          if (xhr.status == 400) {
            this.notesModel.set(() => {
              this.notesModel.commentErrors = xhr.responseJSON;
            });
          } else {
            alert('an error has occurred during request: ' + statusText + '.' + xhr.responseText);
          }
        });
    });
  }

  onStart() {
    var selectedId = this.router.getRouteQueryParam('IdNota');
    if (selectedId != null) {
      this.notesService.findNotes(selectedId)
        .then((note) => {
          this.notesModel.setSelectedNote(note);
        });
    }
  }
}
