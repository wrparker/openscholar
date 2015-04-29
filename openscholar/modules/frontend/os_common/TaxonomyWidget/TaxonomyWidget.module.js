/**
 * Provides mechanisms for choosing a taxonomy term for a given entity
 */
(function () {

  angular.module('TaxonomyWidget', ['EntityService', 'os-auth', 'ui.select', 'ngSanitize', 'ui.bootstrap', 'ui.bootstrap.typeahead'])
    .directive('taxonomyWidget', ['EntityService', function (EntityService) {
      var path = Drupal.settings.paths.TaxonomyWidget;
      return {
        scope: {
          terms: '=',
          bundle: '@'
        },
        templateUrl: path + 'TaxonomyWidget.html',
        link: function (scope, elem, attrs, c, $scope) {
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

              scope.selectedTerms[term.vid][term.id] = term.id;
            }
          }

          // Any change in the selected term scope will affect the file terms.
          // This can be done thanks to a "Two way binding" implements using the
          // = operator which defined in the isolated scope.
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

          scope.autocomplete_terms = [{}];

          /**
           * Add more autocomplete inputs.
           */
          scope.addMore = function() {
            scope.autocomplete_terms.push({});
          };

          /**
           * Add another term to the selected terms object.
           */
          scope.onSelect = function ($item, $model, $label) {
            scope.selectedTerms[$item.vid][$item.id] = $item.id;
          };

          /**
           * Add and remove a term when checking/un-checking the checkbox.
           */
          scope.termsSelected = function(term, object) {
            if (!scope.selectedTerms[term.vid]) {
              scope.selectedTerms[term.vid] = [];

              if (!scope.selectedTerms[term.vid][term.id]) {
                scope.selectedTerms[term.vid][term.id] = term.id;
              }
              else {
                scope.selectedTerms[term.vid][term.id] = null;
              }
            }
            else {
              if (scope.selectedTerms[term.vid][term.id]) {
                scope.selectedTerms[term.vid][term.id] = null;
              }
              else {
                scope.selectedTerms[term.vid][term.id] = term.id;
              }
            }
          };
        }
      }
    }]);

})();
