class NotesModel extends Fronty.Model {

  constructor() {
    super('NotesModel'); //call super

    // model attributes
    this.notes = [];
  }

  setSelectedNote(note) {
    this.set((self) => {
      self.selectedNote = note;
    });
  }

  setNotes(notes) {
    this.set((self) => {
      self.notes = notes;
    });
  }
}
