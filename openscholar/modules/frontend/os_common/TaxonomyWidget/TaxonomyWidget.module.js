/**
 * Provides mechanisms for choosing a taxonomy term for a given entity
 */
(function () {

  angular.module('TaxonomyWidget', ['EntityService', 'os-auth', 'ui.select', 'ngSanitize'])
    .directive('taxonomyWidget', ['EntityService', function (EntityService) {
      var path = Drupal.settings.paths.TaxonomyWidget;
      return {
        scope: {
          terms: '=',
          bundle: '@'
        },
        templateUrl: path + 'TaxonomyWidget.html',
        link: function (scope, elem, attrs, c) {
          var entityType = attrs.entityType;
          var vocabService = new EntityService('vocabulary', 'id');
          var termService = new EntityService('taxonomy', 'id');
          scope.allTerms = {};
          scope.selectedTerms = {};

          // Separate the list of all selected terms by vocab reduces complexity
          // later.
          if (scope.terms) {
            for (var i = 0; i < scope.terms.length; i++) {
              var term = scope.terms[i];
              if (scope.selectedTerms[term.vid] == undefined) {
                scope.selectedTerms[term.vid] = {};
              }

              scope.selectedTerms[term.vid][term.id] = term;
            }
          }

          scope.$watch('selectedTerms', function() {
            scope.terms = scope.selectedTerms;
          }, true);

          // Occurs every time a selected file is changed.
          attrs.$observe('bundle', function (value) {
            if (!value) {
              return;
            }

            vocabService.fetch({entity_type: entityType, bundle: value})
              .then(function (result) {
                if (!(result.status == 200 && result.statusText == "OK")) {
                  return;
                }

                scope.vocabs = result.data.data;
                for (var i=0; i<scope.vocabs.length; i++) {
                  scope.allTerms[scope.vocabs[i].id] = [];
                  termService.fetch({vocab: scope.vocabs[i].id})
                    .then(function (result) {
                      for (var j = 0; j < result.data.data.length; j++) {
                        var t = result.data.data[j];
                        scope.allTerms[parseInt(t.vid)].push(t);
                      }
                    });
                }
              });
          });
        }
      }
    }])

})();
