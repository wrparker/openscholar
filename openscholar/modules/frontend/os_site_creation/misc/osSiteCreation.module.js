(function () {

  var m = angular.module('SiteCreationForm', ['angularModalService', 'redirectForm', 'formElement', 'os-buttonSpinner'])
  .config(function (){
    rootPath = Drupal.settings.paths.siteCreationModuleRoot;
  });

  /**
   * Open modals for the site creation forms
   */
  m.directive('siteCreationForm', ['ModalService', '$rootScope', function (ModalService, $rootScope) {
    var dialogOptions = {
      minWidth: 900,
      minHeight: 300,
      modal: true,
      position: 'center',
      dialogClass: 'site-creation-form',
      title: 'Create your web site',
      dialogClass: 'site-creation-form'
    };

    function link(scope, elem, attrs) {
		
      elem.bind('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $rootScope.siteCreationFormId = attrs.id;

        ModalService.showModal({
          controller: 'siteCreationFormController',
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
  
  /**
   * The controller for the site creation form
   */
  m.controller('siteCreationFormController', ['$scope', '$sce', 'apSettings', 'buttonSpinnerStatus', 'form', 'close', function ($s, $sce, apSettings, bss, form, close) {
    var formSettings = {};
    $s.formId = form;
    $s.formElements = {};
    $s.formData = {};

    $s.status = [];
    $s.errors = [];
    $s.columns = {};
    $s.columnCount = 0;
    $s.showSaveButton = false;

  }]);
})()
