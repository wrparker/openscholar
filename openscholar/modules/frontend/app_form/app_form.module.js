(function () {

  var m = angular.module('AppForm', ['angularModalService']);

  m.directive('appFormModal', ['ModalService', function (ModalService) {
    var dialogOptions = {
      minWidth: 850,
      minHeight: 100,
      modal: true,
      position: 'center',
      dialogClass: 'app-form'
    };

    function clickHandler(e) {
      e.preventDefault();
      e.stopPropagation();

      ModalService.showModal({
        controller: 'appFormController',
        templateUrl: Drupal.settings.paths.app_form + '/app_form.template.html',
      })
      .then(function (modal) {
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
    }
    return {
      link: function (scope, elem, attr) {
        elem.bind('click', clickHandler);
      }
    }
  }]);

  m.controller('appFormController', ['$scope', 'appFormService', function ($s, afs) {
    afs.fetch().then(function (r) {
      console.log(r);
    })
  }]);

  m.service('appFormService', ['$http', '$q', function ($http, $q) {
    var fetchDefered = $q.defer();
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
        fetchDefered.resolve(r.data);
      },
      function (e) {
        fetchDefered.reject(e);
      });

      return fetchDefered.promise;
    }

    return {
      fetch: fetch,
    }
  }]);

})()
