(function () {

  var m = angular.module('redirectForm', ['DependencyManager']);

  m.config(['DependenciesProvider', function ($depProvider) {
    $depProvider.AddDependency('formElement', 'redirectForm');
  }]);

  m.directive('redirects', [function() {
    return {
      template: 'Redirect module activates.',
      scope: {
        value: '=',
        element: '='
      },
      link: function () {

      }
    };
  }]);
})();