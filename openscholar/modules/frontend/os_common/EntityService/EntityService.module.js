/**
 * Provides a generic module for interacting with entities via our REST API
 */
(function () {

  var restPath = '';

  angular.module('EntityService', [])
  /**
   * Service to maintain the list of files on a user's site
   */
    .factory('EntityService', ['$rootScope', '$http', function ($rootScope, $http) {
      var factory = function (entityType, idProp) {
        var type = entityType;
        var entities = [];
        var entityCount = 0;
        var eventName = 'EntityService.'+type;
        var errorAttempts = 0;
        var vsite = null;

        if (Drupal.settings.spaces) {
          vsite = Drupal.settings.spaces.id;
        }

        if (!restPath) {
          restPath = Drupal.settings.paths.api;
        }

        var success = function(resp) {
          for (var i=0; i<resp.data.length; i++) {
            entities.push(resp.data[i]);
          }
          entityCount = resp.count;
          $rootScope.$broadcast(eventName+'.fetch', entities);
        }

        var errorFunc = function() {
          errorAttempts++;
          if (errorAttempts < 3) {
            $http.get({url: restPath}).
              success(success).
              error(errorFunc);
          }
        }

        function findByProp(prop, value) {
          var l = entities.length;
          for (var i =0; i < l; i++) {
            if (entities[i][prop] && entities[i][prop] == value) {
              return i;
            }
          }
        }

        this.fetch = function () {
          var url = restPath+'/'+entityType;
          if (vsite) {
            //url += '?filter[vsite]='+vsite;
          }
          $http.get(url)
            .success(success)
            .error(errorFunc);
        }

        this.getAll = function () {
          return entities;
        }

        this.get = function (id) {
          var k = findByProp(idProp, id);
          if (entities[k]) {
            return entities[k];
          }
          throw new Exception('Entity of type '+type+' with id '+id+' not found.');
        }

        this.getCount = function () {
          return entityCount;
        }

        this.add = function (entity) {
          var k = findByProp(idProp, entity[idProp]);
          if (entities[k]) {
            throw new Exception('Cannot add entity of type '+type+' that already exists.');
          }
          // rest API call to add entity to server
          $http.post(restPath+'/'+entityType, entity)
            .success(function (resp) {
              console.log(resp);
              var entity = resp.data[0];
              entities.push(entity);

              $rootScope.$broadcast(eventName+'.add', entity);
            })
        };

        this.edit = function (entity) {
          if (!entity[idProp]) {
            this.add(entity);
            return;
          }
          var k = fetchByProp(idProp, entity[idProp]);
          entities[k] = entity;
          // rest API call to update entity on server

          $rootScope.$broadcast(eventName+'.update');
        };

        this.delete = function (entity) {
          var k = fetchByProp(idProp, entity[idProp]);
          entities.slice(k, 1);

          //rest API call to delete entity from server

          $rootScope.$broadcast(eventName+'.delete');
        }

        this.fetch();
      }

      return factory;
    }]);
})();