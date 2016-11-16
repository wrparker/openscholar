(function () {

  var m = angular.module('displayTypeForm', ['DependencyManager']);

  m.config(['DependenciesProvider', function ($depProvider) {
    $depProvider.AddDependency('formElement', 'displayTypeForm');
  }]);

  m.directive('displayType', ['$http', 'buttonSpinnerStatus', function($http, bss) {
    return {
      template:

        '<ul class="table-list">' +
          '<li class="redirect-item" ng-repeat="option in options">' +
            '{{option}}' +
          '</li>' +
        '</ul>',

      scope: {
        value: '=',
        element: '='
      },

      link: function (scope, elem, attr) {
        scope.options = scope.element.options;
      }
    };
  }]);
})();
