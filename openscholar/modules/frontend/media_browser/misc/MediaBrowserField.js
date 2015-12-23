(function () {

  angular.module('MediaBrowserField', ['mediaBrowser', 'FileEditorModal', 'EntityService', 'ui.sortable'])
    .directive('mediaBrowserField', ['mbModal', 'EntityService', function (mbModal, EntityService) {

      function link(scope, elem, attr) {
        // everything to define
        scope.field_name = elem.parent().attr('id').match(/edit-([\w-]*)/)[1].replace(/-/g, '_');
        scope.field_id = elem.parent().attr('id');
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

        scope.sortableOptions = {
          axis: 'y',
          handle: '.tabledrag-handle'
        };

        if (scope.selectedFiles.length == 0) {
          var fids = Drupal.settings.mediaBrowserField[elem.parent().attr('id')].selectedFiles,
            service = new EntityService('files', 'id'),
            generateFunc = function (i) {
              return function(file) {
                scope.selectedFiles[i] = angular.copy(file);
                return file;
              }
            };
          for (var i = 0; i<fids.length; i++) {
            var fid = fids[i];
            service.fetchOne(fid).then(generateFunc(i));
          }
        }

        // prefetch the files now so user can open Media Browser later
        service.fetch();

        scope.$on('EntityService.files.update', function (e, file) {
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
          for (var i = 0; i < scope.selectedFiles; i++) {
            highlightDupe(scope.selectedFiles[i], false);
          }
        }

        scope.addFile = function ($files) {
          for (var i = 0; i < $files.length; i++) {
            var found = false;
            for (var j = 0; j < scope.selectedFiles.length; j++) {
              highlightDupe(scope.selectedFiles[j], false);
              if ($files[i].id == scope.selectedFiles[j].id) {
                scope.selectedFiles[j] = angular.copy($files[i]);
                highlightDupe(scope.selectedFiles[j], true);
                found = true;
                break;
              }
            }
            if (!found) {
              scope.selectedFiles.push($files[i]);
            }
          }
        }

        scope.removeFile = function ($index) {
          scope.selectedFiles.splice($index, 1);
        }

        scope.replaceFile = function ($inserted, $index) {
          scope.selectedFiles.splice($index, 1, $inserted[0]);
        }

        function highlightDupe(file, toHighlight) {
          file.highlight = toHighlight;
        }

        var label = elem.parent().find(' label');
        elem.parent().find(' > *').not(elem).remove();
        elem.before(label);
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