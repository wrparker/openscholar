(function () {
  var rootPath = Drupal.settings.osRestModulePath,
      restPath = Drupal.settings.restBasePath;

  angular.module('mediaBrowser', ['mediaBrowser.services', 'mediaBrowser.filters', 'mediaBrowser.directives', 'JSPager'])
  .controller('BrowserCtrl', ['FileService', '$scope', '$filter', '$http', '$templateCache', function (FileService, $scope, $filter, $http, $templateCache) {
    $scope.files = FileService.getAll();
    $scope.templatePath = rootPath;
    $scope.selection = 0;
    $scope.selection_form = '';

    // Watch for changes in file list
    $scope.$on('FileService.changed', function (event, files) {
      $scope.files = files;
      console.log(files);
    });

    // Filter list of files by a filename fragment
    $scope.queryFilename = function (item) {

      if ($scope.search) {
        var search = $scope.search.toLowerCase();
        if (item.name.toLowerCase().indexOf(search) > -1) {
          return true;
        }
        else if (item.orig && item.orig.toLowerCase().indexOf(search) > -1) {
          return true;
        }
        return false;
      }
      return true;
    };

    // selected file
    $scope.setSelection = function (fid) {
      $scope.selection = fid;
      $scope.selected_file = angular.copy(FileService.get(fid));
      $scope.selection_form = rootPath+'/templates/'+$scope.selected_file.form+'.html';
    };

    // preload file edit templates
    $http.get(rootPath+'/templates/file_edit_default.html', {cache:$templateCache});
    $http.get(rootPath+'/templates/file_edit_image.html', {cache:$templateCache});

    // file edit form methods
    $scope.save = function() {
      FileService.edit(selected_file);
    };

    $scope.delete = function() {
      FileService.delete(selected_file);
    };
  }]);
})();