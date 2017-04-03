(function () {

  var m = angular.module('displayTypeForm', ['DependencyManager']);

  m.config(['DependenciesProvider', function ($depProvider) {
    $depProvider.AddDependency('formElement', 'displayTypeForm');
  }]);

  m.directive('displayType', ['$timeout', function($t) {
    return {
      priority: 10,
      link: function (scope, elem, attr) {
        function changeDisplay (e) {
          var key = e.currentTarget.value;
          wrapper.html(scope.element.previews[key]);
        }

        // This needs to be in a $timeout because the input elements haven't
        // been cloned by this point.
        $t(function () {
          var inputs = elem.find('input');
          inputs.bind('click', changeDisplay);
        });

        var wrapper = angular.element('<div id="os-profiles-preview"></div>');
        wrapper.html(scope.element.previews[scope.value]);
        elem.append(wrapper);
      }
    };
  }]);
})();
