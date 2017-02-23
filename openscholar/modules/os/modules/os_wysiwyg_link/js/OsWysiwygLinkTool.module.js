(function () {

  var m = angular.module('OsWysiwygLinkTool', ['MediaBrowser']);

  m.service('OWLModal', ['ModalService', function () {

    return {
      open: function (text, urlArgument, titleText, newWindow, close) {
        ModalService.showModal({
          template: '<div>' +
          '<div class="form-item">' +
            '<label for="owl-field-text"><input type="text" id="owl-field-text" ng-model="text">' +
            '<div class="description">The text of the link. Leave blank to use the url of the link itself.</div>' +
          '</div>' +
          '<div class="form-item">' +
            '<label for="owl-title-text">Title Text</label><input type="text" id="owl-title-text" ng-model="title">' +
            '<div class="description">The text will appear on mouse hover, and is used by screen readers. It will not appear for keyboard or touch-only users.</div>' +
          '</div>' +
          '<div class="form-item">' +
            '<input type="checkbox" id="owl-target" ng-model="newWindow"><label for="owl-target">Open this link in a new tab</label>' +
            '<div class="description">Note: depending on usage, may cause accessibility concenrs. <a href="https://www.w3.org/TR/WCAG20-TECHS/G200.html" target="_blank">Learn more</a></div>' +
          '</div>' +
          '<uib-tabset active="active">' +
            '<uib-tab index="href" id="href-link">' +
              '<div class="form-item">' +
                '<label for="owl-url">Website URL</label><input type="text" id="owl-url" ng-model="url">' +
                '<div class="description">The URL of the web page. Can be a link to one of your own pages.</div>' +
              '</div>' +
            '</uib-tab>' +
            '<uib-tab index="email" id="email-link">' +
              '<label for="owl-email">E-mail</label><input type="text" id="owl-email" ng-model="arg">' +
            '</uib-tab>' +
            '<uib-tab index="file" id="file-link">' +
              '<div class="owl-file-controls">'+
                '<div class="add_new">Add New' +
                  '<div class="form-help">?</div>' +
                  '<div class="description">Files must be less than <strong>{{filesize}}</strong>.<br />Allowed file types: {{extensionStr}}</div>' +
                '</div>' +
                'Or' +
                '<div class="search"></div>' +
              '</div>' +
              '<div class="owl-file-list">' +
                '<div js-pager="f in files" ng-class="{selected: f.id == arg" ng-click="">{{f.name}}' +
                '</div>' +
              '</div>' +
            '</uib-tab>' +
          '</uib-tabset></div>',
          controller: 'OWLModalController',
          inputs: {
            text: text,
            arg: urlArgument,
            title: titleText,
            newWindow: newWindow,
          }
        })
          .then (function (modal) {
          modal.element.dialog(dialogParams);
          modal.close.then(function(result) {
            if (angular.isFunction(close)) {
              close({result: result});
            }
          });
        })
      }
    }
  }]);

  m.controller('OWLModalController', ['$scope', 'EntityService', 'params', 'close', function ($s, EntityService, params, close) {
    var files = new EntityService('files', 'id');

    $s.text = params.text;
    $s.arg = params.arg;
    $s.title = params.title;
    $s.newWindow = params.newWindow;

    $s.files = files.
  }]);

})();