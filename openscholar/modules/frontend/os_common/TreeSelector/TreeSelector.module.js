(function () {

  /**
   * Behavioral spec:
   * Selecting parent does NOT affect selection state of children
   * Selecting children does NOT affect selection state of parent
   * Completely unselected ree should begin completely collapsed
   * All selected nodes must be visible to start
   */

  var m = angular.module('TreeSelector', []);

  m.directive('TreeSelector', [function () {

    function treeLinker(scope, elem, attr, contr, transFn) {

      scope.range = function(n) {
        return new Array(n);
      }

      scope.$watch('tree', function (newTree) {
        scope.flatTree = [];
        for (var i = 0; i < scope.tree.length; i++) {
          scope.flatTree.concat(flattenTree(scope.tree[i]));
        }
      });

      scope.parentCollapsed = function (parent) {
        var node = findNode(parent);
        if (node) {
          return node.collapsed;
        }
        return true;
      }

      function findNode(id) {
        var i = 0,
          l = scope.flatTree.length;

        for(;i<l;i++) {
          if (scope.flatTree[i].value == id) {
            return scope.flatTree[i];
          }
        }
      }

    }

    /**
     * Given a node, returns an array with the node and all its descendants on the same level
     * @param node
     */
    function flattenTree(node) {
      var output = [],
        depth = (node.depth !== undefined) ? node.depth : 0;
        node.collapsed = true;

      if (node.depth === undefined) {
        node.depth = depth;
        node.collapsed = false;
      }
      output.push(node);
      if (Array.isArray(node.children)) {
        node.hasChildren = true;
        node.isLeaf = false;
        for (var i = 0; i < node.children.length; i++) {
          node.children[i].parent = node.value
          node.children[i].depth = depth + 1;
          output.concat(flattenTree(node.children[i]));
        }
      }
      else {
        node.isLeaf = true;
        node.hasChildren = false;
      }

      return output;
    }

    return {
      restrict: 'AE',
      scope: {
        tree: '=',  // proper tree
        selected: '=', // flat array of selected ids
        onChange: '&' // event handler to invoke when a node is changed
      },
      link: treeLinker,
      template: '<ul><li ng-repeat="node as flatTree" ng-class="{collapsed: parentCollapsed(node.parent)}">' +
        '<span class="spacer" ng-repeat="i in range(node.depth)"></span>' +
        '<span class="expander" ng-click="node.collapsed = false" ng-class="{collapsed: node.collapsed}">&nbsp;</span>' +
        '<input type="checkbox" value="node.value" ng-change="onChange(node.value)" ng-checked="isChecked(node.value)"> {{node.label}}' +
      '</li></ul>'
    };

  }]);
});