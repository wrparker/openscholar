(function () {

  var m = angular.module('grouper', []);

  m.directive('grouper', ['$http', function ($http) {
    return {
      templateUrl: Drupal.settings.paths.grouper,
      link: function (scope, elem, attrs) {

      }
    }
  }]);
})();