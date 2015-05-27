/**
 * Provides mechanisms for choosing a taxonomy term for a given entity.
 */
var taxonomy = angular.module('TaxonomyWidget', ['EntityService', 'os-auth', 'ui.select', 'ngSanitize', 'ui.bootstrap', 'ui.bootstrap.typeahead', 'ivh.treeview']);

taxonomy.directive('taxonomyWidget', ['EntityService', function (EntityService) {
  var path = Drupal.settings.paths.TaxonomyWidget;
  return {
    restrict: 'E',
    scope: {
      terms: "=",
      bundle: "@"
    },
    templateUrl: path + '/TaxonomyWidget.html',
    link: function (scope, elem, attrs) {
      var entityType = attrs.entityType;
      var vocabService = new EntityService('vocabulary', 'id');
      var termService = new EntityService('taxonomy', 'id');
      scope.allTerms = {};
      scope.termsTree = [];

      // Any change in the selected term scope will affect the file terms.
      // This can be done thanks to a "Two way binding" implements using the
      // = operator which defined in the isolated scope.
      scope.$watch('terms', function() {
        scope.selectedTerms = scope.terms;
      }, true);

      // Occurs every time a selected file is changed.
      attrs.$observe('bundle', function (value) {
        if (!value) {
          return;
        }

        vocabService.fetch({entity_type: entityType, bundle: value}).then(function (result) {
          if (!(result.status == 200 && result.statusText == "OK")) {
            return;
          }

          scope.vocabs = result.data.data;
          for (var i=0; i<scope.vocabs.length; i++) {
            var vocab = scope.vocabs[i];

            scope.termsTree[vocab.id] = vocab.tree;
            scope.allTerms[vocab.id] = [];

            termService.fetch({vocab: vocab.id}).then(function (result) {
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
        scope.terms = scope.selectedTerms;
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

      scope.termTreeChangeCallback = function(node, tree) {

        if (Object.keys(node).indexOf('children') != -1) {
          // Iterating over the children's of the term.
          angular.forEach(node.children, function(value, key) {
            scope.termTreeChangeCallback(value);
          });
        }

        scope.termsSelected(termService.get(node.value));
      };
    }
  }
}]);
