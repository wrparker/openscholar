/**
 * Provides mechanisms for choosing a taxonomy term for a given entity
 */
(function ($) {

  angular.module('TaxonomyWidget', ['EntityService', 'os-auth'])
    .directive('taxonomyWidget', ['EntityService', function (EntityService) {
      return {
        scope: {
          vocabId: '@',
          terms: '='
        },
        template: '<div class="tw-widget"><div ng-include="widgetType"></div></div>',
        link: function (scope, elem, attrs, c, trans) {
          var vocabService = new EntityService('vocabulary', 'id'),
            vocab = vocabService.get(scope.vocabId),
            termService = new EntityService('terms', 'id'),
            terms = termService.getAll({vocab: scope.vocabId});

          scope.widgetType = vocab.something;
          scope.allTerms = [];
          scope.selectedTerms = {};
        }
      }
    }])

})(jQuery);
