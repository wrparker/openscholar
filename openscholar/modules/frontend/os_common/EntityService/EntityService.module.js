/**
 * Provides a generic module for interacting with entities via our REST API
 */
(function () {

  var rootPath = Drupal.settings.osRestModulePath,
    restPath = Drupal.settings.restBasePath;

  angular.module('EntityService', [])
  /**
   * Service to maintain the list of files on a user's site
   */
    .factory('EntityService', ['$rootScope', '$http', function ($rootScope, $http) {

      var factory = function (entityType, idProp) {
        var type = entitytype;
        var entities = {};
        var eventName = 'EntityService.'+type;

        $http.get(restPath+'/'+entityType)
          .success(function success(data) {
            for (var i=0; i<data.list.length; i++) {
              var id = data.list[i][idProp];
              entities[id] = data.list[i];
            }
            $rootScope.$broadcast(eventName+'.getAll', entities);
          })
          .error(function errorFunc() {
            $http.get({url: restPath}).
              success(success).
              error(errorFunc);
          });

        this.getAll = function () {
          return entities;
        }

        this.get = function (id) {
          if (entities[id]) {
            return entities[id];
          }
          throw new Exception('Entity of type '+type+' with id '+id+' not found.');
        }

        this.add = function (entity) {
          if (entities[entity[idProp]]) {
            throw new Exception('Cannot add entity of type '+type+' that already exists.');
          }
          entities[entity[idProp]] = entity;
          // rest API call to add entity to server

          $rootScope.$broadcast(eventName+'.add', entity);
        };

        this.edit = function (entity) {
          if (!entities[entity[idProp]]) {
            this.add(entity);
            return;
          }
          entities[entity[idProp]] = entity;
          // rest API call to update entity on server

          $rootScope.$broadcast(eventName+'.update');
        };

        this.delete = function (entity) {
          var id = entity[idProp];
          delete entities[id];

          //rest API call to delete entity from server

          $rootScope.$broadcast(eventName+'.delete');
        }
      }

      return factory;
    }]);
})