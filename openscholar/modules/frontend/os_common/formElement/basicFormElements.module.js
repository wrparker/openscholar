(function () {

  var m = angular.module('basicFormElements', ['osHelpers']);

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

})();
