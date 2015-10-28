(function ($) {
  var libraryPath = '';

  angular.module('FileEditor', ['EntityService', 'os-auth', 'TaxonomyWidget']).
    config(function () {
      libraryPath = Drupal.settings.paths.FileEditor;
    }).
    directive('fileEdit', ['EntityService', '$http', '$timeout', '$filter', function (EntityService, $http, $timeout, $filter) {
      return {
        scope: {
          file :  '=',
          onClose : '&'
        },
        templateUrl: libraryPath + '/file_edit_base.html?vers='+Drupal.settings.version.FileEditor,
        link: function (scope, elem, attr, c, trans) {
          var fileService = new EntityService('files', 'id');
          //fileService.fetch({});

          scope.fileEditAddt = '';
          scope.date = '';

          scope.$watch('file', function (f) {
            if (!f) return;
            scope.fileEditAddt = libraryPath+'/file_edit_'+f.type+'.html?vers='+Drupal.settings.version.FileEditor;
            scope.date = $filter('date')(f.timestamp+'000', 'short');
            scope.file.terms = scope.file.terms || [];

            scope.fullPath = f.url.slice(0, f.url.lastIndexOf('/')+1);
            scope.extension = '.' + getExtension(f.url);
          });

          var dateTimeout;
          scope.$watch('date', function (value, old) {
            if (value == old ) return;
            scope.invalidDate = false;
            var d = new Date(value);
            if (isNaN(d.getTime())) {
              scope.invalidDate = true;
              return;
            }
            if (d) {
              scope.file.timestamp = parseInt(d.getTime() / 1000);
              if (dateTimeout) {
                $timeout.cancel(dateTimeout);
              }
              dateTimeout = $timeout(function () {
                scope.date = $filter('date')(scope.file.timestamp+'000', 'short');
              }, 3000);
            }
          });

          scope.$watch('file.filename', function (filename, old) {
            if (typeof filename != 'string') {
              return;
            }
            scope.invalidName = false;
            if (filename == "") {
              scope.invalidName = true;
              return;
            }
            if (!filename.match(/^([a-zA-Z0-9_\.-]*)$/)) {
              scope.invalidName = true;
              return;
            }
            var lower = filename.toLowerCase();
            if (lower != old) {
              var files = fileService.getAll();
              for (var i in files) {
                if (lower == files[i].filename && scope.file.id != files[i].id) {
                  scope.invalidName = true;
                  return;
                }
              }
            }
          });

          scope.showWarning = false;
          scope.showSuccess = false;
          scope.replaceReject = false;
          scope.validate = function ($file) {
            if (!$file) return true;

            if (getExtension(scope.file.url) == getExtension($file.name)) {
              return true;
            }

            return false;
          };

          scope.displayWarning = function() {
            scope.showWarning = true;
          };

          scope.prepForReplace = function ($files, $event, $rejectedFiles) {
            if ($event.type != 'change') return;
            if ($files.length) {
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

              $http.put(Drupal.settings.paths.api + '/files/' + scope.file.id, file, config)
                .success(function (result) {
                  scope.showWarning = false;
                  scope.replaceSuccess = true;

                  $timeout(function () {
                    scope.replaceSuccess = false;
                  }, 5000);
                });
            }
            else {
              scope.replaceReject = true;
              $timeout(function () {
                scope.replaceReject = false;
              }, 5000);
            }
          };

          scope.canSave = function () {
            return scope.invalidName;
          }

          scope.save = function () {
            fileService.edit(scope.file, ['preview', 'url']).then(function(result) {
                if (result) {
                  scope.onClose({saved: true});
                }
                else {
                  scope.onClose({saved: false});
                }
            },
            function(result) {
              scope.errorMessages = result.data.title.replace(/[^\s:]*: /, '');
              scope.showErrorMessages = true;
            });
          };

          scope.cancel = function () {
            scope.onClose({saved: false});
          }
        }
      }
    }]);

  function getExtension(url) {
    return url.slice(url.lastIndexOf('.')+1, (url.lastIndexOf('?') != -1)?url.lastIndexOf('?'):url.length).toLowerCase();
  }

})(jQuery);
