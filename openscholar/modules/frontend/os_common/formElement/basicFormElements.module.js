(function () {

  var m = angular.module('basicFormelements', []);

  m.directive('checkbox', function () {
    return {
      scope: {
        value: '=',
      },
      template: '<input type="checkbox" id="{{id}}" name="{{variable}}" value="1" class="form-checkbox" ng-checked="value" />'
    }
  })

})();
