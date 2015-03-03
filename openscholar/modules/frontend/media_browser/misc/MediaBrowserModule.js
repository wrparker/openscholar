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

    // selected file
    $scope.setSelection = function (fid) {
      $scope.selection = fid;
      $scope.selected_file = angular.copy(service.get(fid));
      switch ($scope.selected_file.type) {
        case 'image':
          $scope.selection_form = rootPath+'/templates/file_edit_image.html';
          break;
        default:
          $scope.selection_form = rootPath+'/templates/file_edit_default.html';
      }
    };

    // preload file edit templates
    $http.get(rootPath+'/templates/file_edit_default.html', {cache:$templateCache});
    $http.get(rootPath+'/templates/file_edit_image.html', {cache:$templateCache});

    // file edit form methods
    $scope.save = function() {
      service.edit($scope.selected_file);
    };

    $scope.deleteConfirmed = function() {
      service.delete($scope.selected_file);
    };
  }]);
})();