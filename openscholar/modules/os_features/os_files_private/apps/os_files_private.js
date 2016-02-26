(function() {
  angular.module('os-files-private', ['mediaBrowser', 'FileEditorModal'])
    .config(['EntityConfigProvider', function(ecp) {
      var elem = angular.element(document.querySelectorAll('.view-id-os_files_private'));
      elem.attr('ng-controller', 'OSFilesPrivateController');

      ecp.addField('files', 'private', 'only');
    }])
    .controller('OSFilesPrivateController', ['$scope', 'FILEEDITOR_RESPONSES', function ($scope, FER) {
      $scope.reload = function (result) {
        var reload = false;
        if (result == FER.SAVED || result == FER.REPLACED) {
          reload = true;
        }
        if (Array.isArray(result) && result.length) {
          reload = true;
        }
        if (result === true) {
          reload = true;
        }

        if (reload) {
          window.location.reload();
        }
      }
    }]);
})();