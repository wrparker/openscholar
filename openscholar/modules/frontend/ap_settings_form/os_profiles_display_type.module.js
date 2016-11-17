(function () {

  var m = angular.module('displayTypeForm', ['DependencyManager']);

  m.config(['DependenciesProvider', function ($depProvider) {
    $depProvider.AddDependency('formElement', 'displayTypeForm');
  }]);

  m.directive('displayType', ['$http', 'buttonSpinnerStatus', function($http, bss) {
    return {
      template:

        '<ul>' +
          '<li class="redirect-item" ng-repeat="(option, label) in options">' +
            '<input type="radio" name="os_profiles_display_type" value="{{option}}" /> {{label}}' +
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
