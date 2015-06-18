(function () {

  angular.module('MediaBrowserField', ['mediaBrowser', 'FileEditorModal', 'EntityService'])
    .directive('media-browser-field', [function () {

      function link(scope, elem, attr) {
        // everything to define
        scope.field_name;
        scope.droppable_area_text;
        scope.showHelp = false;
        scope.maxFilesize
        scope.extensions
        scope.panes
        scope.types
        scope.selectedFiles = [];
        scope.sendToBrowser = function($files) {

        }

        scope.addFile = function ($files) {
          for (var i = 0; i < $files.length; i++) {
            scope.selectedFiles.push($files[i]);
          }
        }

        scope.removeFile = function ($index) {
          scope.selectedFiles.splice($index);
        }

        scope.replaceFile = function ($inserted, $index) {
          scope.selectedFiles.splice($index, 1, $inserted);
        }
      }

      return {
        link: link,
        templateUrl: Drupal.settings.paths.moduleRoot + '/templates/field.html'
      }
    }])
})();