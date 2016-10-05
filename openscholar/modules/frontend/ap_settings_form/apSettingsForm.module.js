(function () {

  var m = angular.module('ApSettingsForm', ['angularModalService', 'redirectForm', 'MediaBrowserField', 'formElement']);

  /**
   * Fetches the settings forms from the server and makes them available directives and controllers
   */
  m.service('apSettings', ['$http', '$q', function ($http, $q, $httpParamSerializer) {

    var settingsForms = {};
    var settings = {};
    var groupInfo = {};
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

      for (var varName in data.data) {
        var varForm = data.data[varName];
        var group = varForm.group['#id'];

        settingsForms[group] = settingsForms[group] || {};
        settingsForms[group][varName] = varForm.form;

        groupInfo[group] = varForm.group;

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
      throw "No form group with id " + group_id + " exists.";
    }

    this.GetFormTitle = function (group_id) {
      if (typeof groupInfo[group_id] != 'undefined') {
        return groupInfo[group_id]['#title'];
      }
      throw "No form group with the id " + group_id + " exists.";
    }

    this.GetHelpLink = function (group_id) {
      if (typeof groupInfo[group_id] != 'undefined') {
        return groupInfo[group_id]['#help_link'];
      }
      throw "No form group with the id " + group_id + " exists.";
    }

    this.SaveSettings = function (settings) {
      console.log(settings);

      return $http.put(baseUrl+'/settings', settings, config);
    }

  }]);

  /**
   * Open modals for the settings forms
   */
  m.directive('apSettingsForm', ['ModalService', 'apSettings', function (ModalService, apSettings) {
    var dialogOptions = {
      minWidth: 800,
      minHeight: 100,
      modal: true,
      position: 'center',
      dialogClass: 'ap-settings-form'
    };

    function link(scope, elem, attrs) {
      apSettings.SettingsReady().then(function () {
        scope.title = apSettings.GetFormTitle(scope.form);
      })

      elem.bind('click', function (e) {
        event.preventDefault();
        event.stopPropagation();

        ModalService.showModal({
          controller: 'apSettingsFormController',
          template: '<form id="{{formId}}" ng-submit="submitForm()"><div class="form-wrapper"><div class="form-item" ng-repeat="(key, field) in formElements | weight">' +
            '<div form-element element="field" value="formData[key]"><span>placeholder</span></div>' +
          '</div>' +
          '<div class="help-link" ng-bind-html="help_link"></div></div>' +
          '<div class="actions"><input type="submit" value="Save"><input type="button" value="Close" ng-click="close(false)"></div></form>',
          inputs: {
            form: scope.form
          }
        })
        .then(function (modal) {
          dialogOptions.title = scope.title;
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
  m.controller('apSettingsFormController', ['$scope', '$sce', 'apSettings', 'form', 'close', function ($s, $sce, apSettings, form, close) {
    var formSettings = {};
    $s.formId = form;
    $s.formElements = {};
    $s.formData = {};

    apSettings.SettingsReady().then(function () {
      var settingsRaw = apSettings.GetFormDefinitions(form);
      console.log(settingsRaw);

      $s.help_link = $sce.trustAsHtml(apSettings.GetHelpLink(form));

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

        if (attributes.value) {
          attributes.origValue = attributes.value;
        }
        //attributes.value = 'formData[' + k + ']';
        $s.formElements[k] = attributes;
      }
    });

    function submitForm() {
      apSettings.SaveSettings($s.formData).then(function (response) {
        sessionStorage['messages'] = JSON.stringify(response.data.messages);
        $s.close(true);
      });
    }
    $s.submitForm = submitForm;

    $s.close = function (arg) {
      close(arg);
    }
  }]);
})()