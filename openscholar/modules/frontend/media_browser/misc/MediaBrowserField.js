(function () {

  angular.module('MediaBrowserField', ['mediaBrowser', 'FileEditorModal', 'EntityService'])
    .directive('mediaBrowserField', ['mbModal', 'EntityService', function (mbModal, EntityService) {

      function link(scope, elem, attr) {
        // everything to define
        scope.field_name = elem.parent().attr('id').match(/edit-([\w-]*)/)[1].replace('-', '_');
        scope.showHelp = false;
        scope.panes = ['upload', 'web', 'library'];

        var types = {};
        scope.allowedTypes = scope.types.split(',');
        scope.extensionsFull = [];
        for (var i = 0; i < scope.allowedTypes.length; i++) {
          var type = scope.allowedTypes[i];
          types[type] = type;
          if (Drupal.settings.extensionMap[type] && Drupal.settings.extensionMap[type].length) {
            scope.extensionsFull = scope.extensionsFull.concat(Drupal.settings.extensionMap[type]);
          }
        }
        scope.extensionsFull.sort();

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

        scope.$on('EntityService.files.update', function (file) {
          for (var i = 0; i<scope.selectedFiles.length; i++) {
            if (file.id == scope.selectedFiles[i].id) {
              scope.selectedFiles[i] = angular.copy(file);
            }
          }
        });

        scope.sendToBrowser = function($files) {
          var params = {
            files: $files,
            onSelect: scope.addFile,
            types: types
          };
          mbModal.open(params);
        }

        scope.addFile = function ($files) {
          for (var i = 0; i < $files.length; i++) {
            scope.selectedFiles.push($files[i]);
          }
        }

        scope.removeFile = function ($index) {
          scope.selectedFiles.splice($index, 1);
        }

        scope.replaceFile = function ($inserted, $index) {
          scope.selectedFiles.splice($index, 1, $inserted[0]);
        }

        elem.parent().find(' > *').not(elem).remove();
      }

      if (mbModal.requirementsMet()) {
        return {
          link: link,
          templateUrl: function () {
            return Drupal.settings.paths.moduleRoot + '/templates/field.html?vers=' + Drupal.settings.version.mediaBrowser
          },
          scope: {
            selectedFiles: '=files',
            maxFilesize: '@maxFilesize',
            types: '@',
            extensions: '@',
            upload_text: '@uploadText',
            droppable_text: '@droppableText'
          }
        }
      }
      else {
        // remove this element. It won't work right anyway
        return {
          link: function (elem, attr) {
            elem.remove();
          }
        }
      }
    }])
})();