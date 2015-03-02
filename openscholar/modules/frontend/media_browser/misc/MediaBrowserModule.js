(function () {
  var rootPath = Drupal.settings.osRestModulePath,
      restPath = Drupal.settings.restBasePath;

  angular.module('mediaBrowser', ['JSPager', 'EntityService', 'mediaBrowser.filters'])
  .controller('BrowserCtrl', ['$scope', '$filter', '$http', '$templateCache', 'EntityService', '$sce',
      function ($scope, $filter, $http, $templateCache, EntityService, $sce) {
    var service = new EntityService('files', 'id');
    $scope.files = [];
    $scope.numFiles = 0;
    $scope.templatePath = rootPath;
    $scope.selection = 0;
    $scope.selection_form = '';

        $scope.getId = function () {
          return $scope.$id;
        }

    // Watch for changes in file list
    $scope.$on('EntityService.files.add', function (event, files) {
      $scope.files = files;
    });

    $scope.$on('EntityService.files.getAll', function (event, files) {
      $scope.files = files;
      for (var i=0; i<$scope.files.length; i++) {
        $scope.files.preview = $sce.trustAsHtml($scope.files.preview);
      }
      $scope.numFiles = service.getCount();
    });

    // Filter list of files by a filename fragment
    $scope.queryFilename = function (item, index) {

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