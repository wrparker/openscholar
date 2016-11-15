(function () {

  var m = angular.module('ApSettingsFormProfiles', ['angularModalService', 'redirectForm', 'MediaBrowserField', 'formElement', 'os-buttonSpinner']);

  /**
   * Open modals for the settings forms
   */
  m.directive('apSettingsFormProfiles', ['ModalService', 'apSettings', function (ModalService, apSettings) {
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
      });

      elem.bind('click', function (e) {
        debugger;
        e.preventDefault();
        e.stopPropagation();

        ModalService.showModal({
          controller: 'ApSettingsFormProfilesController',
          template: '<form id="{{formId}}" name="settingsForm" ng-submit="submitForm($event)">' +
            '<div class="messages" ng-show="status.length || errors.length"><div class="dismiss" ng-click="status.length = 0; errors.length = 0;">X</div>' +
              '<div class="status" ng-show="status.length > 0"><div ng-repeat="m in status">{{m}}</div></div>' +
              '<div class="error" ng-show="errors.length > 0"><div ng-repeat="m in errors">{{m}}</div></div></div>' +
            '</div>' +
            '<div class="form-wrapper">' +
              '<div class="form-item" ng-repeat="(key, field) in formElements | weight">' +
                '<div form-element element="field" value="formData[key]"><span>placeholder</span></div>' +
              '</div>' +
            '<div class="help-link" ng-bind-html="help_link"></div></div>' +
          '<div class="actions"><button type="submit" button-spinner="settings_form" spinning-text="Saving">Save</button><input type="button" value="Close" ng-click="close(false)"></div></form>',
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
  m.controller('ApSettingsFormProfilesController', ['$scope', '$sce', 'apSettings', 'buttonSpinnerStatus', 'form', 'close', function ($s, $sce, apSettings, bss, form, close) {
    var formSettings = {};
    $s.formId = form;
    $s.formElements = {};
    $s.formData = {};

    $s.status = [];
    $s.errors = [];

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

    function submitForm($event) {
      var button = document.activeElement,
        triggered = false;
      if (apSettings.IsSetting(button.getAttribute('name'))) {
        triggered = true;
      }

      if ($s.settingsForm.$dirty || triggered) {
        bss.SetState('settings_form', true);
        apSettings.SaveSettings($s.formData).then(function (response) {
          var body = response.data;
          sessionStorage['messages'] = JSON.stringify(body.data.messages);
          $s.status = [];
          $s.error = [];
          var close = true;
          var reload = true;
          bss.SetState('settings_form', false);
          for (var i = 0; i < body.data.length; i++) {
            switch (body.data[i].type) {
              case 'no_close':
                close = false;
              case 'no_reload':
                reload = false;
                break;
              case 'message':
                $s[body.data[i].message_type].push(body.data[i].message)
            }
          }
          if (close) {
            $s.close(reload);
          }
        }, function (error) {
          $s.errors = [];
          $s.status = [];
          $s.errors.push("Sorry, something went wrong. Please try another time.");
          bss.SetState('settings_form', false);
        });
      }
      else {
        $s.close(false);
      }
    }
    $s.submitForm = submitForm;

    $s.close = function (arg) {
      close(arg);
    }
  }]);
})();
