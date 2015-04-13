/**
 * Provides mechanisms for choosing a taxonomy term for a given entity
 */
(function ($) {

  angular.module('TaxonomyWidget', ['EntityService', 'os-auth'])
    .directive('taxonomyWidget', ['EntityService', function (EntityService) {
      return {
        scope: {
          terms: '='
        },
        template: '<div class="tw-widget"><div ng-include="widgetType"></div></div>',
        link: function (scope, elem, attrs, c, trans) {
          var entityType = attrs.entitytype,
            entityBundle = attrs.bundle,
            vocabService = new EntityService('vocabulary', 'id'),
            termService = new EntityService('terms', 'id'),
            terms = termService.getAll({vocab: scope.vocabId});

          vocabService.fetch({entity_type: entityType, bundle: entityBundle})
            .then(function (result) {
              console.log(result);
              scope.vocabs = result;
              for (var i=0; i<scope.vocabs.length; i++) {
                termService.fetch({vocab: scope.vocabs[i].id})
                  .then(function (result) {
                    console.log(result);
                    for (var j=0; j<scope.vocabs.length; j++) {

                    }
                  });
              }
            });


        }
      }
    }])

})(jQuery);
