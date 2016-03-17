(function () {

  var m = angular.module('ApSettingsForm', ['angularModalService']);

  /**
   * Fetches the settings forms from the server and makes them available directives and controllers
   */
  m.service('apSettings', ['$http', '$q', function ($http, $q, $httpParamSerializer) {

    var settingsForms = {};
    var settings = {};
    var promises = [];

    var queryArgs = {};

    if (Drupal.settings.spaces.id) {
      queryArgs.vsite = Drupal.settings.spaces.id;
    }

    var baseUrl = Drupal.settings.paths.api;
    var config = {
      params: queryArgs
    };

    // fetch all the form data from the server
    promises.push($http.get(baseUrl+'/settings', config).then(function (response) {
      var data = response.data;

      console.log(data);
      for (var varName in data.data) {
        var varForm = data.data[varName];
        var group = varForm.group['#id'];

        settingsForms[group] = settingsForms[group] || {};
        settingsForms[group][varName] = varForm.form;

        settings[varName] = varForm.form['#default_value'];
      }
      return response;
    }));

    var allPromise = $q.all(promises);

    this.SettingsReady = function() {
      return allPromise;
    }

    this.GetFormDefinitions = function (group_id) {
      if (typeof settingsforms[group_id] != 'undefined') {
        return angular.copy(settingsForms[group_id]);
      }
     throw new Exception("No form group with id " + group_id + " exists.");
    }

  }]);

  /**
   * testing. delete when done.
   */
  m.run(['apSettings', function (apSettings) {

  }]);

  /**
   * Open modals for the settings forms
   */
  m.directive('apSettingsForm', ['ModalService', 'apSettings', function (ModalService, apSettings) {
    function link(scope, elem, attrs) {
      console.log('ap setting form directive active');
    }

    return {
      link: link
    };
  }]);

  /**
   * The controller for the forms themselves
   */
  m.controller('apSettingsFormController', ['$scope', 'apSettings', function ($s, apSettings) {

  }]);
})()