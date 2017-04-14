(function () {

  var m = angular.module('AppForm', ['angularModalService']);

  m.directive('appFormModal', ['ModalService', function (modal) {
    var dialogOptions = {
      minWidth: 850,
      minHeight: 100,
      modal: true,
      position: 'center',
      dialogClass: 'ap-settings-form'
    };

    function clickHandler(e) {
      e.preventDefault();
      e.stopPropagation();

      modal.openModal({
        controller: 'appformController',
        templateUrl: 'app_form.template.html',
      });
    }
    return {
      link: function (scope, elem, attr) {
        elem.bind('click', function (e) {

        })
      }
    }
  }]);

  m.controller('appFormController', ['$scope', function ($s) {

  }]);

  m.service('appFormService', ['$http', '$q', function ($http, $q) {
    function fetch() {
      var queryArgs = {};
      if (Drupal.settings.spaces.id) {
        queryArgs.vsite = Drupal.settings.spaces.id;
      }

      var baseUrl = Drupal.settings.paths.api;
      var config = {
        params: queryArgs
      };

      $http.get(baseUrl + '/apps', config).then(function (r) {
        console.log(r);
      },
      function (e) {

      });
    }
  }]);

})()
