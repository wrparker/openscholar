(function () {

  var m = angular.module('OsWysiwygLinkTool', ['EntityService', 'JSPager', 'angularModalService']);

  m.service('OWLModal', ['ModalService', function (ModalService) {
    var dialogParams = {
      buttons: {},
      //dialogClass: 'media-wrapper',
      modal: true,
      draggable: false,
      resizable: false,
      minWidth: 600,
      width: 800,
      height: 650,
      position: 'center',
      title: undefined,
      overlay: {
        backgroundColor: '#000000',
        opacity: 0.4
      },
      zIndex: 10000,
      close: function (event, ui) {
        $(event.target).remove();
      }
    };

    return {
      open: function (text, type, urlArgument, titleText, newWindow, close) {
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
                '<div js-pager="f in files" class="owl-file-item" ng-class="{selected: f.id == arg}" ng-click="arg = f.id">{{f.name}}' +
                '</div>' +
              '</div>' +
            '</uib-tab>' +
          '</uib-tabset>' +
          '<div class="form-actions">' +
            '<button ng-click="close(true)">Insert</button>' +
            '<button ng-click="close(false)">Close</button>' +
          '</div>' +
          '</div>',
          controller: 'OWLModalController',
          inputs: {
            params: {
              text: text,
              type: type,
              arg: urlArgument,
              title: titleText,
              newWindow: newWindow,
            }
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
    $s.active = params.type;

    var params = {
      schema: 'public'
    };

    files.fetch(params).then(function (result) {
      $s.files = result;
    });

    $s.close = function (insert) {
      ret = {
        type: $s.active,
        arg: $s.arg,
        text: $s.text,
        title: $s.title,
        newWindow: $s.newWindow,
        insert: insert
      }

      close(ret);
    }
  }]);

  m.run(['OWLModal', '$timeout', function (modal, $t) {

    function replacement(editorId, info, callback) {
      function closeHandler() {
        console.log(arguments);
        var body = '',
          target = '',
          attributes = {};

        callback(editorId, body, target, attributes);
      }
      modal.open(info.text, info.type, info.url, info.title, info.newWindow, closeHandler)
    }

    try {
      Drupal.wysiwyg.plugins.os_link.openModal = replacement;
    }
    catch (e) {
      console.log("Attempting to access plugin too early.");
    }
  }]);

})();