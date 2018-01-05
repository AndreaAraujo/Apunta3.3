class MainComponent extends Fronty.RouterComponent {
  constructor() {
    super('frontyapp', Handlebars.templates.main, 'maincontent');

    // models instantiation
    // we can instantiate models at any place
    var userModel = new UserModel();
    var notesModel = new NotesModel();

    super.setRouterConfig({
      notes: {
        component: new NotesComponent(notesModel, userModel, this),
        title: 'Notes'
      },
      'view-note': {
        component: new NoteViewComponent(notesModel, userModel, this),
        title: 'Note'
      },
      'edit-note': {
        component: new NoteEditComponent(notesModel, userModel, this),
        title: 'Edit Note'
      },
      'add-note': {
        component: new NoteAddComponent(notesModel, userModel, this),
        title: 'Add Note'
      },

      /*
      'share-post': {
          component: new PostShareComponent(postsModel, userModel, this),
          title: 'Share Post'
      },
      'shared-post': {
          component: new PostSharedComponent(postsModel, userModel, this),
          title: 'Shared Post'
      },*/
      login: {
        component: new LoginComponent(userModel, this),
        title: 'Login'
      },
      defaultRoute: 'notes'
    });

    Handlebars.registerHelper('currentPage', () => {
          return super.getCurrentPage();
    });

    var userService = new UserService();
    this.addChildComponent(this._createUserBarComponent(userModel, userService));
    this.addChildComponent(this._createLanguageComponent());

  }

  _createUserBarComponent(userModel, userService) {
    var userbar = new Fronty.ModelComponent(Handlebars.templates.user, userModel, 'userbar');

    userbar.addEventListener('click', '#logoutbutton', () => {
      userModel.logout();
      userService.logout();
    });

    // do relogin
    userService.loginWithSessionData()
      .then(function(logged) {
        if (logged != null) {
          userModel.setLoggeduser(logged);
        }
      });

    return userbar;
  }

  _createLanguageComponent() {
    var languageComponent = new Fronty.ModelComponent(Handlebars.templates.language, this.routerModel, 'languagecontrol');
    // language change links
    languageComponent.addEventListener('click', '#englishlink', () => {
      I18n.changeLanguage('default');
      document.location.reload();
    });

    languageComponent.addEventListener('click', '#spanishlink', () => {
      I18n.changeLanguage('es');
      document.location.reload();
    });

    return languageComponent;
  }
}
