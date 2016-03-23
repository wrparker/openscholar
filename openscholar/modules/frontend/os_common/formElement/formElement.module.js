(function () {

  var m = angular.module('formElement', ['basicFormElements', 'osHelpers']);

  m.directive('formElement', ['$compile', '$filter', function ($compile, $filter) {
    return {
      scope: {
        element: '=',
        value: '='
      },
      template: '<label for="{{id}}">{{label}}</label>' +
      '<span>Placeholder</span>' +
      '<div class="description">{{description}}</div>',
      link: function (scope, elem, attr) {
        console.log(scope.element);
        scope.id = $filter('idClean')(scope.element.id);

        var copy = elem.find('span').clone();
        for (var k in scope.element) {
          if (k == 'type') {
            copy.attr(scope.element[k], '');
          }
          else {
            copy.attr(k, scope.element[k]);
          }

          copy.attr('value', scope.value);
          copy = $compile(copy)(scope);
          elem.find('span').replaceWith(copy);
        }
      }
    }
  }]);

})();