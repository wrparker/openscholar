(function() {
  var m = angular.module('ContentAddForm', ['angularModalService', 'MediaBrowserField', 'formElement', 'os-buttonSpinner']);

    /**
   * Fetches the content settings forms from the server and makes them available to directives and controllers
   */
  m.service('contentForm', ['$http', '$q', function ($http, $q, $httpParamSerializer) {

  }])

  /**
   * Open modals for the content settings forms
   */
  m.directive('contentAddForm', ['ModalService', 'contentForm', function (ModalService, contentForm) {
    var dialogOptions = {
      minWidth: 1187,
      minHeight: 100,
      modal: true,
      position: 'center',
      dialogClass: 'content-add-form'
    };

    function link(scope, elem, attrs) {


      elem.bind('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        ModalService.showModal({
          controller: 'contentAddFormController',
          template: '<form id="{{formId}}" name="contentAddForm" ng-submit="submitForm($event)">' +
            '<div class="messages" ng-show="status.length || errors.length"><div class="dismiss" ng-click="status.length = 0; errors.length = 0;">X</div>' +
              '<div class="status" ng-show="status.length > 0"><div ng-repeat="m in status track by $index"><span ng-bind-html="m"></span></div></div>' +
              '<div class="error" ng-show="errors.length > 0"><div ng-repeat="m in errors track by $index"><span ng-bind-html="m"></span></div></div></div>' +
            '</div>' +
            '<div class="form-column-wrapper column-count-{{columnCount}}" ng-if="columnCount > 1">' +
              '<div class="form-column column-{{column_key}}" ng-repeat="(column_key, elements) in columns">' +
                '<div class="form-item" ng-repeat="(key, field) in elements | weight">' +
                  '<div form-element element="field" value="formData[key]"><span>placeholder</span></div>' +
                '</div>' +
              '</div>' +
            '</div>' +
            '<div class="form-wrapper" ng-if="columnCount == 1">' +
              '<div class="form-item" ng-repeat="(key, field) in formElements | weight">' +
                '<div form-element element="field" value="formData[key]"><span>placeholder</span></div>' +
              '</div>' +
            '</div>' +
            '<div class="help-link" ng-bind-html="help_link"></div>' +
          '<div class="actions" ng-show="showSaveButton"><button type="submit" button-spinner="settings_form" spinning-text="Saving">Save</button><input type="button" value="Close" ng-click="close(false)"></div></form>',
          inputs: {
            form: scope.form
          }
        })
        .then(function (modal) {
          dialogOptions.title = 'Hallo';
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
   * The controller for the forms themselves
   */
  m.controller('contentAddFormController', ['$scope', '$sce', 'contentForm', 'buttonSpinnerStatus', 'form', 'close', function ($s, $sce, contentForm, bss, form, close) {

  }]);

})()
