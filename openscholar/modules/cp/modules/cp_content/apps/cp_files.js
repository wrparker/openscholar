(function() {
  angular.module('cp-files', ['mediaBrowser', 'FileEditorModal'])
    .run([function() {
      var elem = angular.element(document.querySelectorAll('.view-id-cp_files'));
      elem.attr('ng-controller', 'CpFilesController');
    }])
    .controller('CpFilesController', ['$scope', 'FILEEDITOR_RESPONSES', function ($scope, FER) {
      $scope.reload = function (result) {
        if (result == FER.SAVED || result === true) {
          window.location.reload();
        }
      }
    }]);
})();