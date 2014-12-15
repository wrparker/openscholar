'use strict';

/**
 * @ngdoc function
 * @name mediaBrowserApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the mediaBrowserApp
 */
angular.module('mediaBrowserApp')
  .controller('MainCtrl', function ($scope) {
    $scope.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];
  });
