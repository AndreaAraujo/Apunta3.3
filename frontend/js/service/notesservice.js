class NotesService {
  constructor() {

  }

  findAllNotes() {
    return $.get(AppConfig.backendServer+'/rest/note');
  }

  findNote(IdNota) {
    return $.get(AppConfig.backendServer+'/rest/note/' + IdNota);
  }

 findNoteShared() {
	 return $.get(AppConfig.backendServer+'/rest/shared');
 }

/*  findPostS(IdNota) {alert(IdNota+ " ");
    return $.get(AppConfig.backendServer+'/rest/share/' + IdNota);
  }*/

  shareNote(IdNota, user) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/note/' + IdNota + '/share',
      method: 'POST',
      data: JSON.stringify(user),
      contentType: 'application/json'
    });
  }

  deleteNote(IdNota) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/note/' + IdNota,
      method: 'DELETE'
    });
  }

  saveNote(note) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/note/' + note.IdNota,
      method: 'PUT',
      data: JSON.stringify(note),
      contentType: 'application/json'
    });
  }

  addNote(note) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/note',
      method: 'POST',
      data: JSON.stringify(note),
      contentType: 'application/json'
    });
  }

}
