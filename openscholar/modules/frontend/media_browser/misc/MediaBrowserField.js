(function () {

  angular.module('MediaBrowserField', ['mediaBrowser', 'FileEditorModal', 'EntityService'])
    .directive('mediaBrowserField', ['mbModal', 'EntityService', function (mbModal, EntityService) {

      function link(scope, elem, attr) {
        // everything to define
        scope.field_name = elem.parent().attr('id').match(/edit-([\w-]*)/)[1].replace('-', '_');
        scope.showHelp = false;
        scope.panes = ['upload', 'web', 'library'];
        console.log(scope);

        if (scope.selectedFiles.length == 0) {
          try {
            var fids = Drupal.settings.mediaBrowserField[elem.parent().attr('id')].selectedFiles,
              service = new EntityService('files', 'id');

            service.fetch().then(function () {
              for (var i = 0; i<fids.length; i++) {
                scope.selectedFiles.push(angular.copy(service.get(fids[i])));
              }
            })
          }
          catch (e) {
            // do nothing
            // assume the entity has no files attached to it yet
          }
        }

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