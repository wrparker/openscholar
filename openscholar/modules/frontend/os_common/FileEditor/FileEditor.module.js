(function ($) {
  var libraryPath = '';

  angular.module('FileEditor', ['EntityService', 'os-auth', 'TaxonomyWidget', 'angularFileUpload']).
    config(function () {
      libraryPath = Drupal.settings.paths.FileEditor;
    }).
    directive('fileEdit', ['EntityService', '$http', '$timeout', '$filter', function (EntityService, $http, $timeout, $filter) {
      return {
        scope: {
          file :  '=',
          onClose : '&'
        },
        templateUrl: libraryPath + '/file_edit_base.html',
        link: function (scope, elem, attr, c, trans) {
          var fileService = new EntityService('files', 'id');

          scope.fileEditAddt = '';
          scope.date = '';

          scope.$watch('file', function (f) {
            if (!f) return;
            scope.fileEditAddt = libraryPath+'/file_edit_'+f.type+'.html';
            scope.date = $filter('date')(f.timestamp+'000', 'short');
            scope.file.terms = (function(terms){
              if (terms == undefined) {
                return;
              }

              var processedTerms = {};
              for (var i = 0; i < terms.length; i++) {
                var term = terms[i];
                if (processedTerms[term.vid] == undefined) {
                  processedTerms[term.vid] = [];
                }

                processedTerms[term.vid].push(term);
              }
              return processedTerms;
            })(scope.file.terms);
          });

          scope.$watch('date', function (value, old) {
            if (value == old ) return;
            var d = new Date(value);
            if (d) {
              scope.file.timestamp = parseInt(d.getTime() / 1000);
            }
          });

          scope.$watch('file.filename', function (filename, old) {
            scope.invalidName = false;
            if (filename && filename != old) {
              var files = fileService.getAll();
              for (var i = 0; i < files.length; i++) {
                if (filename == files[i].filename && scope.file.id != files[i].id) {
                  scope.invalidName = true;
                  return;
                }
              }
            }
          });

          scope.showWarning = false;
          scope.showSuccess = false;
          scope.validate = function ($file) {
            if (!$file) return true;

            if (scope.file.mimetype == $file.type) {
              return true;
            }

            return false;
          };

          scope.displayWarning = function() {
            scope.showWarning = true;
          };

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
          };

          scope.canSave = function () {
            return scope.invalidName;
          }

          scope.save = function () {
            console.log(scope.file);
            fileService.edit(scope.file);
            scope.onClose({saved: true});
          };

          scope.cancel = function () {
            scope.onClose({saved: false});
          }
        }
      }
    }])

})(jQuery);
