/**
 * Provides mechanisms for choosing a taxonomy term for a given entity
 */
(function ($) {

  angular.module('TaxonomyWidget', ['EntityService', 'os-auth'])
    .directive('taxonomyWidget', ['EntityService', function (EntityService) {
      var path = Drupal.settings.paths.TaxonomyWidget;
      return {
        scope: {
          terms: '='
        },
        templateUrl: path + '/TaxonomyWidget.html',
        link: function (scope, elem, attrs, c, trans) {
          var entityType = attrs.entitytype,
            entityBundle = attrs.bundle,
            vocabService = new EntityService('vocabulary', 'id'),
            termService = new EntityService('terms', 'id');

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
