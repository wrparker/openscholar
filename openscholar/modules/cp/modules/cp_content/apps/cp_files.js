(function() {
  angular.module('cp-files', ['mediaBrowser', 'FileEditorModal'])
    .run([function() {
      var elem = angular.element(document.querySelectorAll('.view-id-cp_files'));
      elem.attr('ng-controller', 'CpFilesController');
    }])
    .controller('CpFilesController', ['$scope', function ($scope) {
      $scope.reload = function (result) {
        if (result) {
          window.location.reload();
        }
      }
    }]);
})();