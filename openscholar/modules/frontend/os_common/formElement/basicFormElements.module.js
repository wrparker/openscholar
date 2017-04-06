(function () {

  var m = angular.module('basicFormElements', ['osHelpers', 'ngSanitize']);

  /**
   * Select directive.
   */
  m.directive('feSelect', ['$sce', function ($sce) {
    return {
      scope: {
        name: '@',
        value: '=ngModel',
        element: '='
      },
      template: '<label for="{{id}}">{{title}}</label>' +
        '<div class="form-item form-type-select"><select class="form-select" id="{{id}}" name="{{name}}" ng-model="value">' +
          '<option value="">Select</option>' +
          '<option ng-repeat="(val, label) in options" value="{{val}}" ng-bind-html="label"></option>' +
        '</select></div>',
      link: function (scope, elem, attr) {
        scope.id = attr['inputId'];
        scope.options = scope.element.options;
        scope.title = scope.element.title;
      }
    }
  }]);

  /**
   * Checkboxes directive.
   */
  m.directive('feCheckboxes', ['$sce', function ($sce) {
    return {
      require: 'ngModel',
      scope: {
        name: '@',
        value: '=ngModel',
        element: '='
      },
      template: '<label for="{{id}}">{{title}}</label>' +
      '<div id="{{id}}" class="form-checkboxes">' +
        '<div ng-show="element.select_all">' +          
          '<input ng-model="selectAll" type="checkbox" class="form-checkbox" ng-disabled="element.disabled" ng-change="masterChange()">' + 
          '&nbsp;<label class="option bold">Select All</label>' +
        '</div>' +
        '<div ng-if="element.sorted_options" class="form-item form-type-checkbox" ng-repeat="option in options | orderBy: \'label\'">' +
          '<input ng-model="value[option.key]" ng-checked="value[option.key]" type="checkbox" id="{{id}}-{{option.key}}" name="{{name}}" value="{{option.key}}" class="form-checkbox" ng-disabled="element.disabled">' + 
          '&nbsp;<label class="option" for="{{id}}-{{option.key}}" ng-bind-html="option.label"></label>' +
        '</div>' +
        '<div ng-if="!element.sorted_options" class="form-item form-type-checkbox" ng-repeat="option in options">' +
          '<input ng-model="value[option.key]" ng-checked="value[option.key]" type="checkbox" id="{{id}}-{{option.key}}" name="{{name}}" value="{{option.key}}" class="form-checkbox" ng-disabled="element.disabled">' + 
          '&nbsp;<label class="option" for="{{id}}-{{option.key}}" ng-bind-html="option.label"></label>' +
        '</div>' +
      '</div> ',
      link: function (scope, elem, attr) {
        scope.id = attr['inputId'];
        scope.options = scope.element.options;       
        scope.title = scope.element.title;

        scope.masterChange = function () {
          if (scope.selectAll) {
            angular.forEach(scope.options, function (cb) {
              scope.value[cb.key] = true;
            });
          } else {   
            angular.forEach(scope.options, function (cb) {
              scope.value[cb.key] = false;
            });
          }
        };
      }
    }
  }]);

  /**
   * Checkbox directive.
   * Arguments:
   *   name - string - the name of the element as Drupal expects it
   *   value - property on parent scope
   */
  m.directive('feCheckbox', [function () {
    return {
      require: 'ngModel',
      scope: {
        name: '@',
        value: '=ngModel',
        element: '='
      },
      template: '<input type="checkbox" id="{{id}}" name="{{name}}" value="1" class="form-checkbox" ng-model="value" ng-disabled="element.disabled" ng-true-value="1" ng-false-value="0"/><label class="option" for="{{id}}">{{title}}</label>',
      link: function (scope, elem, attr, ngModelController) {
        scope.id = attr['inputId'];
        scope.title = scope.element.title;
      }
    }
  }]);

  /**
   * Textbox directive.
   */
  m.directive('feTextfield', [function () {
    return {
      scope: {
        name: '@',
        value: '=ngModel',
        element: '='
      },
      template: '<label for="{{id}}">{{title}}</label>' +
      '<input type="textfield" id="{{id}}" name="{{name}}" ng-model="value" class="form-text" ng-disabled="element.disabled">',
      link: function (scope, elem, attr) {
        scope.id = attr['inputId'];
        scope.title = scope.element.title;
      }
    }
  }]);

  /**
   * Textarea directive
   */
  m.directive('feTextarea', [function () {
    return {
      scope: {
        name: '@',
        value: '=ngModel',
        element: '='
      },
      template: '<label for="{{id}}">{{title}}</label>' +
      '<textarea id="{{id}}" name="{{name}}" ng-model="value" class="form-textarea" ng-disabled="element.disabled"></textarea>',
      link: function (scope, elem, attr) {
        scope.id = attr['inputId'];
        scope.title = scope.element.title;
      }
    }
  }]);

  /**
   * Radios directive.
   */
  m.directive('feRadios', ['$sce', function ($sce) {
    return {
      scope: {
        name: '@',
        value: '=ngModel',
        element: '='
      },
      template: '<label for="{{id}}">{{title}}</label>' +
      '<div id="{{id}}" class="form-radios">' +
        '<div class="form-item form-type-radio" ng-repeat="(val, label) in options">' +
          '<input type="radio" id="{{id}}-{{val}}" name="{{name}}" value="{{val}}" ng-model="$parent.value" class="form-radio" ng-disabled="element.disabled"><label class="option" for="{{id}}-{{val}}" ng-bind-html="label"></label>' +
        '</div>' +
      '</div> ',
      link: function (scope, elem, attr) {
        scope.id = attr['inputId'];
        scope.options = scope.element.options;
        scope.title = scope.element.title;
      }
    }
  }]);

  /**
   * Submit button directive
   *
   * This type of form element should always have some kind of handler on the server end to take care of whatever this needs to do.
   */
  m.directive('feSubmit', [function () {
    return {
      scope: {
        name: '@',
        value: '=ngModel',
        element: '='
      },
      template: '<label for="{{id}}">{{title}}<input type="submit" id="{{id}}" name="{{name}}" value="{{label}}" class="form-submit" ng-disabled="element.disabled">',
      link: function (scope, elem, attr) {
        scope.id = attr['inputId'];
        scope.label = scope.element.value;
        scope.title = scope.element.title;

        elem.click(function (click) {
          scope.value = (scope.value + 1)%2;
        });
      }
    }
  }]);

  /**
   * Markup directive.
   *
   * Just markup that doesn't do anything.
   */
  m.directive('feMarkup', ['$sce', function ($sce) {
    return {
      scope: {
        name: '@',
        value: '=ngModel',
        element: '=',
      },
      template: '<div ng-bind-html="markup"></div>',
      link: function (scope, elem, attr) {
        scope.id = attr['inputId'];
        scope.markup = $sce.trustAsHtml(scope.element.markup);
        scope.title = scope.element.title;
      }
    }
  }]);

  /**
   * Help directive.
   *
   * Just markup that doesn't do anything.
   */
  m.directive('feHelp', ['$timeout', '$sce', function ($timeout, $sce) {
    var gsfnCounter = 0;
    return {
      scope: {
        name: '@',
        value: '=ngModel',
        element: '=',
      },
      template: '<div class="getsat-widget" id="getsat-widget-{{counter}}-{{gsfnid}}"><span class="description"></span><span class="gsfn-loading">Loading...<img src="{{loading}}"></span></div>',
      link: function (scope, elem, attr) {
        scope.gsfnid = scope.element.gsfnId;
        scope.title = scope.element.title;
        scope.counter = gsfnCounter;
        scope.loading = scope.element.loading;
        gsfnCounter = gsfnCounter + 1;
        $timeout(function() {
          GSFN.loadWidget(scope.gsfnid, {"containerId":"getsat-widget-" + scope.counter + "-" + scope.gsfnid});
        }, 2000);
      }
    }
  }]);

  /**
   * Publication cititation js directive.
   *
   * .
   */
  m.directive('fePubjsevent', [function () {
    return {
      scope: {
        name: '@',
        value: '=ngModel',
        element: '=',
      },
      template: '<div ng-bind-html="markup"></div>',
      link: function (scope, elem, attr) {
        angular.element(document.querySelectorAll(scope.element.mouseover_element)).find('div').on(scope.element.mouseover_event, function (e) {
          e.stopPropagation();
          if (scope.element.hide_element) {
            angular.element(document.querySelectorAll(scope.element.hide_element)).hide();
          }
          if (scope.element.show_element) {
            if (scope.element.show_element == 'this.id') {
              var pop_id = angular.element(this).find('input').attr('value').replace('.','');
              angular.element(document.querySelectorAll('#' + pop_id)).show();
            } else {
              angular.element(document.querySelectorAll(scope.element.show_element)).show();
            }
          }
        });
      }
    }
  }])

})();