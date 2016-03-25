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
        scope.id = $filter('idClean')(scope.element.name, 'edit');
        scope.description = scope.element.description;
        scope.label = scope.element.title;

        var copy = elem.find('span').clone();
        for (var k in scope.element) {
          if (k == 'type') {
            copy.attr(scope.element[k], '');
          }
          else if (k == 'name') {
            copy.attr('name', scope.element[k]);
          }
        }

        console.log(scope);

        copy.attr('input-id', scope.id);
        copy.attr('value', 'value');
        copy = $compile(copy)(scope);
        elem.find('span').replaceWith(copy);
      }
    }
  }]);

})();