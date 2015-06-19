(function () {

  angular.module('MediaBrowserField', ['mediaBrowser', 'FileEditorModal', 'EntityService'])
    .directive('mediaBrowserField', ['mbModal', function (mbModal) {

      function link(scope, elem, attr) {
        // everything to define
        scope.field_name = elem.parent().attr('id').match(/edit-([.]*)/)[1];
        scope.showHelp = false;
        scope.panes = ['upload', 'web', 'library'];
        scope.sendToBrowser = function($files) {
          var params = {
            files: $files,
            onSelect: scope.addFile
          };
          mbModal.open(params);
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
        templateUrl: Drupal.settings.paths.moduleRoot + '/templates/field.html',
        scope: {
          selectedFiles: '=files',
          maxFilesize: '@maxFilesize',
          types: '@',
          extensions: '@',
          upload_text: '@uploadText',
          droppable_text: '@droppableText'
        }
      }
    }])
})();