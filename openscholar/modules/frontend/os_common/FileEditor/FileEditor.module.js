(function ($) {
  var libraryPath = '';

  angular.module('FileEditor', ['EntityService', 'os-auth', 'TaxonomyWidget', 'ng-file-upload']).
    config(function () {
      libraryPath = Drupal.settings.paths.FileEditor;
    })
    directive('fileEdit', ['EntityService', '$upload', '$timeout', function (EntityService, $upload, $timeout) {
      return {
        scope: {
          file :  '=',
          onClose : '&',
        },
        templateUrl: libraryPath+'/file_edit_base.html',
        link: function (scope, elem, attr, c, trans) {
          var fileService = new EntityService('files', 'id');

          scope.fileEditAddt = libraryPath+'/file_edit_'+scope.file.type+'.html';
          scope.showWarning = false;
          scope.showSuccess = false;
          scope.validate = function ($files) {
            var newFile = $files[0];

            if (scope.file.type == newFile.type) {
              return true;
            }

            return false;
          }

          scope.displayWarning = function() {
            scope.showWarning = true;
          }

          scope.prepForUpload = function ($files, $event) {
            var file = $files[0];

            $upload.upload({
              url: Drupal.settings.paths.api+'/files/'+file.id,
              file: file,
              data: file,
              fileFormDataName: 'files[upload]',
              headers: {'Content-Type': $file.type},
              method: 'PUT',
              fields: fields,
            }).success(function () {
              scope.showWarning = false;
              scope.replaceSuccess = true;
              $timeout(function () {
                scope.replaceSuccess = false;
              }, 5000);
            })
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
