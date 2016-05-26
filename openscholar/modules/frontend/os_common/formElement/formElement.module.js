(function () {

  var m = angular.module('formElement', ['basicFormElements', 'osHelpers', 'ngSanitize', 'DependencyManager']);

  m.run(['Dependencies', function (dm) {
    var deps = dm.GetDependencies();
    Array.prototype.push.apply(m.requires, deps);
  }]);

  m.directive('formElement', ['$compile', '$filter', '$sce', '$timeout', function ($compile, $filter, $sce, $t) {
    return {
      scope: {
        element: '=',
        value: '='
      },
      template: '<span ng-if="element.prefix" ng-bind-html="element.prefix"></span>' +
      '<span>Placeholder</span>' +
      '<div class="description" ng-bind-html="description"></div>',
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

        copy.attr('element', 'element');

        copy.attr('input-id', scope.id);
        copy.attr('value', 'value');
        copy = $compile(copy)(scope);
        elem.find('span').replaceWith(copy);
        if (scope.element.attached) {
          $t(function () {
            Drupal.behaviors.states.attach(jQuery(elem), scope.element.attached.js[0].data);
          });
        }
      }
    }
  }]);

})();