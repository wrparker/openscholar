/**
 * Provides mechanisms for choosing a taxonomy term for a given entity
 */
(function ($) {

  angular.module('TaxonomyWidget', ['EntityService', 'os-auth'])
    .directive('taxonomyWidget', ['EntityService', function (EntityService) {
      var path = Drupal.settings.paths.TaxonomyWidget;
      return {
        scope: {
          terms: '=',
          bundle: '@',
        },
        templateUrl: path + '/TaxonomyWidget.html',
        link: function (scope, elem, attrs, c, trans) {
          var entityType = attrs.entityType,
            vocabService = new EntityService('vocabulary', 'id'),
            termService = new EntityService('taxonomy', 'id');
          console.log(scope);

          attrs.$observe('bundle', function (value) {
            console.log(value);

            if (!value) return;
            vocabService.fetch({entity_type: entityType, bundle: value})
              .then(function (result) {
                console.log(result);
                if (!(result.status == 200 && result.statusText == "OK")) {
                  return;
                }

                scope.vocabs = result.data.data;
                for (var i=0; i<scope.vocabs.length; i++) {
                  termService.fetch({vocab: scope.vocabs[i].id})
                    .then(function (result) {
                      console.log(result);
                      for (var j=0; j<scope.vocabs.length; j++) {

                      }
                    });
                }
              });
          });
        }
      }
    }])

})(jQuery);
