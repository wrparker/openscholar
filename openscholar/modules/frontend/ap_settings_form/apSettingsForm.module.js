(function () {

  var m = angular.module('ApSettingsForm', ['angularModalService', 'formElement']);

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
      if (typeof settingsForms[group_id] != 'undefined') {
        return angular.copy(settingsForms[group_id]);
      }
     throw new Exception("No form group with id " + group_id + " exists.");
    }

    this.SaveSettings = function (settings) {
      console.log(settings);

      $http.put(baseUrl+'/settings', settings, config);
    }

  }]);

  /**
   * Open modals for the settings forms
   */
  m.directive('apSettingsForm', ['ModalService', 'apSettings', function (ModalService, apSettings) {
    var dialogOptions = {
      minWidth: 800,
      minHeight: 650,
      modal: true,
      position: 'center'
    };

    function link(scope, elem, attrs) {
      elem.bind('click', function (e) {
        event.preventDefault();
        event.stopPropagation();

        ModalService.showModal({
          controller: 'apSettingsFormController',
          template: '<form id="{{formId}}" ng-submit="submitForm()"><div class="form-item" ng-repeat="(key, field) in formElements">' +
            '<div form-element element="field" value="formData[key]"><span>placeholder</span></div>' +
          '</div>' +
          '<div class="actions"><input type="submit" value="Submit"><input type="button" value="Cancel" ng-click="close(false)"></div></form>',
          inputs: {
            form: scope.form
          }
        })
        .then(function (modal) {
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
  m.controller('apSettingsFormController', ['$scope', 'apSettings', 'form', 'close', function ($s, apSettings, form, close) {
    var formSettings = {};
    $s.formId = form;
    $s.formElements = {};
    $s.formData = {};

    apSettings.SettingsReady().then(function () {
      var settingsRaw = apSettings.GetFormDefinitions(form);
      console.log(settingsRaw);

      for (var k in settingsRaw) {
        $s.formData[k] = settingsRaw[k]['#default_value'] || null;
        var attributes = {
          name: k
        };
        for (var j in settingsRaw[k]) {
          if (j.indexOf('#') === 0 && j != '#default_value') {
            var attr = j.substr(1, j.length);
            attributes[attr] = settingsRaw[k][j];
          }
        }

        attributes.value = 'formData[' + k + ']';
        $s.formElements[k] = attributes;
      }
    });

    function submitForm() {
      apSettings.SaveSettings($s.formData);
      $s.close(true);
    }
    $s.submitForm = submitForm;

    $s.close = function (arg) {
      close(arg);
    }
  }]);
})()