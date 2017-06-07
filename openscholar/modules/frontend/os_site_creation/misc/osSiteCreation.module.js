(function () {

  var m = angular.module('SiteCreationForm', ['angularModalService'])
  .config(function (){
    rootPath = Drupal.settings.paths.siteCreationModuleRoot;
  });

  /**
   * Open modals for the site creation forms
   */
  m.directive('siteCreationForm', ['ModalService','$rootScope', function (ModalService,$rootScope) {
    var dialogOptions = {
      minWidth: 900,
      minHeight: 300,
      modal: true,
      position: 'center',
      dialogClass: 'site-creation-form'
    };

    function link(scope, elem, attrs) {
		
      elem.bind('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $rootScope.siteCreationFormId = attrs.id;

        ModalService.showModal({
          controller: 'siteCreationCtrl',
          templateUrl: rootPath+'/templates/os_site_creation.html',
          inputs: {
            form: scope.form
          }
        })
        .then(function (modal) {
          dialogOptions.title = scope.title;
          dialogOptions.close = function (event, ui) {
            modal.element.remove();
          }
          modal.element.dialog(dialogOptions);
          modal.close.then(function (result) {
            if (result) {
              window.location.reload();
            }
          });
        });
      });
    }

    return {
      link: link,
      scope: {
        form: '@'
      }
    };
  }]);

})()
