/* Main mvcblog-front script */

//load external resources
function loadTextFile(url) {
  return new Promise((resolve, reject) => {
    $.get({
      url: url,
      cache: true,
      dataType: 'text'
    }).then((source) => {
      resolve(source);
    }).fail(() => reject());
  });
}


// Configuration
var AppConfig = {
  backendServer: 'http://localhost/Apunta3.2'
  //backendServer: '/mvcblog'
}

Handlebars.templates = {};
Promise.all([
  I18n.initializeCurrentLanguage('js/i18n'),
   loadTextFile('templates/components/main.hbs').then((source) =>
     Handlebars.templates.main = Handlebars.compile(source)),
   loadTextFile('templates/components/language.hbs').then((source) =>
     Handlebars.templates.language = Handlebars.compile(source)),
   loadTextFile('templates/components/user.hbs').then((source) =>
     Handlebars.templates.user = Handlebars.compile(source)),
   loadTextFile('templates/components/login.hbs').then((source) =>
     Handlebars.templates.login = Handlebars.compile(source)),
   loadTextFile('templates/components/notes-table.hbs').then((source) =>
     Handlebars.templates.notestable = Handlebars.compile(source)),
   loadTextFile('templates/components/note-edit.hbs').then((source) =>
     Handlebars.templates.noteedit = Handlebars.compile(source)),
   loadTextFile('templates/components/note-view.hbs').then((source) =>
     Handlebars.templates.noteview = Handlebars.compile(source)),
   loadTextFile('templates/components/note-row.hbs').then((source) =>
     Handlebars.templates.noterow = Handlebars.compile(source))/*,
   loadTextFile('templates/components/postshared-table.hbs').then((source) =>
     Handlebars.templates.postsharedtable = Handlebars.compile(source)),
   loadTextFile('templates/components/post-share.hbs').then((source) =>
     Handlebars.templates.postshare = Handlebars.compile(source))*/
  ])
  .then(() => {
    $(() => {
      new MainComponent().start();
    });
  }).catch((err) => {
    alert('FATAL: could not start app ' + err);
  });
