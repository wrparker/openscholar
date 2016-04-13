(function () {

  var m = angular.module('basicFormElements', ['osHelpers', 'ngSanitize']);

  /**
   * Checkbox directive.
   * Arguments:
   *   name - string - the name of the element as Drupal expects it
   *   value - property on parent scope
   */
  m.directive('checkbox', [function () {
    return {
      scope: {
        name: '@',
        value: '=',
      },
      template: '<input type="checkbox" id="{{id}}" name="{{name}}" value="1" class="form-checkbox" ng-model="value" ng-true-value="1" ng-false-value="0"/>',
      link: function (scope, elem, attr) {
        scope.id = attr['inputId'];
      }
    }
  }]);

  /**
   * Textbox directive.
   */
  m.directive('textfield', [function () {
    return {
      scope: {
        name: '@',
        value: '=',
      },
      template: '<input type="textfield" id="{{id}}" name="{{name}}" ng-model="value" class="form-text">',
      link: function (scope, elem, attr) {
        scope.id = attr['inputId'];
      }
    }
  }]);

  /**
   * Radios directive.
   */
  m.directive('radios', ['$sce', function ($sce) {
    return {
      scope: {
        name: '@',
        value: '=',
        element: '='
      },
      template: '<div id="{{id}}" class="form-radios">' +
        '<div class="form-item form-type-radio" ng-repeat="(val, label) in options">' +
          '<input type="radio" id="{{id}}-{{val}}" name="{{name}}" value="{{val}}" ng-model="$parent.value" class="form-radio"><label class="option" for="{{id}}-{{val}}" ng-bind-html="label"></label>' +
        '</div>' +
      '</div> ',
      link: function (scope, elem, attr) {
        scope.id = attr['inputId'];
        scope.options = scope.element.options;
      }
    }
  }]);

})();
