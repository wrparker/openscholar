(function () {

  var m = angular.module('AppForm', ['angularModalService']);

  m.directive('appFormModal', ['ModalService', function (ModalService) {
    var dialogOptions = {
      minWidth: 850,
      minHeight: 100,
      modal: true,
      position: 'top+100px',
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
          if (result == 'reload') {
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

  m.controller('appFormController', ['$scope', 'appFormService', 'close', function ($s, afs, close) {
    $s.apps = [];
    afs.fetch().then(function (r) {
      console.log(r);
      for (var k in r.data) {
        r.data[k].privacyLevel = r.data[k].private;
        $s.apps.push(r.data[k]);
      };
    });

    $s.submit = function () {
      for (var i = 0; i < $s.apps.length; i++) {
        $s.apps[i].private = $s.apps[i].privacyLevel;
        if ($s.apps[i].disable) {
          $s.apps[i].enabled = false;
        }
        else if ($s.apps[i].enable) {
          $s.apps[i].enabled = true;
        }
      }

      afs.save($s.apps).then(function (status) {
        if (status == 'success') {
          close('reload');
        }
        else {
          close();
        }
      })
    }

    $s.close = function () {
      close();
    }
  }]);

  m.service('appFormService', ['$http', '$q', '$timeout', function ($http, $q, $t) {
    var pristine = [];
    var map = {};
    var fetchDefered;
    function fetch() {
      if (!fetchDefered) {
        fetchDefered = $q.defer();
        var queryArgs = {};
        if (Drupal.settings.spaces.id) {
          queryArgs.vsite = Drupal.settings.spaces.id;
        }

        var baseUrl = Drupal.settings.paths.api;
        var config = {
          params: queryArgs
        };

        $http.get(baseUrl + '/apps', config).then(function (r) {
          pristine = angular.copy(r.data.data);
          fetchDefered.resolve(r.data);

          for (var i = 0; i < r.data.data.length; i++) {
            map[r.data.data[i].machine_name] = i;
          }
        },
        function (e) {
          fetchDefered.reject(e);
        });
      }

      return fetchDefered.promise;
    }

    var saveDefer;
    function save(values) {
      if (saveDefer) {
        var cancel = $q.defer();
        $t(function () {
          cancel.reject('progress');
        });
        return cancel.promise;
      }

      var queryArgs = {};
      if (Drupal.settings.spaces.id) {
        queryArgs.vsite = Drupal.settings.spaces.id;
      }

      var baseUrl = Drupal.settings.paths.api;
      var config = {
        params: queryArgs
      };

      saveDefer = $q.defer();
      var dirty = [];
      for (var i = 0; i < values.length; i++) {
        var machineName = values[i].machine_name,
          key = map[machineName],
          pris = pristine[key];

        for (var k in pris) {
          if (pris[k] != values[i][k]) {
            dirty.push(values[i]);
            break;
          }
        }
      }

      if (dirty.length == 0) {
        $t(function () {
          saveDefer.resolve('no changes');
        });
      }
      else {
        $http.patch(baseUrl + '/apps', dirty, config).then(function (r) {
          // success
          saveDefer.resolve('success');
        }, function (e) {
          console.log(e);
          saveDefer.reject('error');
        });
      }

      return saveDefer.promise;
    }

    return {
      fetch: fetch,
      save: save
    }
  }]);

  m.run(['appFormService', function (afs) {
    afs.fetch();
  }]);

})()
