(function() {
  angular.module('cp-files', ['mediaBrowser', 'FileEditorModal'])
    .run(['$window', '$timeout', function($w, $t) {
      var elem = angular.element(document.querySelectorAll('.view-id-cp_files'));
      elem.attr('ng-controller', 'CpFilesController');

      var hash = $w.location.hash;
      if (hash == '#open') {
        var elem = document.querySelector('.add_new');
        elem = angular.element(elem);
        $t(function () {
          elem.triggerHandler('click');
        }, 0);
      }
    }])
    .controller('CpFilesController', ['$scope', 'FILEEDITOR_RESPONSES', function ($scope, FER) {
      $scope.reload = function (result) {
        if (result == FER.SAVED || result === true) {
          window.location.reload();
        }
      }
    }]);
})();