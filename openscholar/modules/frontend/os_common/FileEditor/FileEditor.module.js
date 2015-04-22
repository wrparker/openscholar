(function ($) {
  var libraryPath = '';

  angular.module('FileEditor', ['EntityService', 'os-auth', 'TaxonomyWidget', 'angularFileUpload']).
    config(function () {
      libraryPath = Drupal.settings.paths.FileEditor;
    }).
    directive('fileEdit', ['EntityService', '$http', '$timeout', function (EntityService, $http, $timeout) {
      return {
        scope: {
          file :  '=',
          onClose : '&',
        },
        templateUrl: libraryPath+'/file_edit_base.html',
        link: function (scope, elem, attr, c, trans) {
          var fileService = new EntityService('files', 'id');

          scope.fileEditAddt = '';
          if (scope.file) {
            scope.fileEditAddt = libraryPath+'/file_edit_'+scope.file.type+'.html';
          }
          scope.showWarning = false;
          scope.showSuccess = false;
          scope.validate = function ($file) {
            if (!$file) return true;

            if (scope.file.mimetype == $file.type) {
              return true;
            }

            return false;
          }

          scope.displayWarning = function() {
            scope.showWarning = true;
          }

          scope.prepForReplace = function ($files, $event) {
            if (!$files.length) return;
            var file = $files[0],
              fields = {};

            if (typeof Drupal.settings.spaces != 'undefined') {
              fields.vsite = Drupal.settings.spaces.id
            }

            var config = {
              headers: {
                'Content-Type': file.type
              }
            };

            $http.put(Drupal.settings.paths.api+'/files/'+scope.file.id, file, config)
              .success(function () {
                scope.showWarning = false;
                scope.replaceSuccess = true;
                $timeout(function () {
                  scope.replaceSuccess = false;
                }, 5000);
              });
          }

          scope.save = function () {
            fileService.edit(scope.file);
            scope.onClose({saved: true});
          }

          scope.cancel = function () {
            scope.onClose({saved: false});
          }
        }
      }
    }])

})(jQuery);
