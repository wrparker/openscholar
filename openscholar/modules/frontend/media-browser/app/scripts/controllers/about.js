'use strict';

/**
 * @ngdoc function
 * @name mediaBrowserApp.controller:AboutCtrl
 * @description
 * # AboutCtrl
 * Controller of the mediaBrowserApp
 */
angular.module('mediaBrowserApp')
  .controller('AboutCtrl', function ($scope) {
    $scope.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];
  });
