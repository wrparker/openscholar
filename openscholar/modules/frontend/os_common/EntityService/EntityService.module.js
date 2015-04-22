/**
 * Provides a generic module for interacting with entities via our REST API
 */
(function () {

  var restPath = '',
    entities = {};

  angular.module('EntityService', [])
    .config(function () {
      restPath = Drupal.settings.paths.api;
    })
  /**
   * Service to maintain the list of files on a user's site
   */
    .factory('EntityService', ['$rootScope', '$http', function ($rootScope, $http) {
      var factory = function (entityType, idProp) {
        var type = entityType;
        var ents;
        entities[entityType] = ents = entities[entityType] || [];
        var entityCount = 0;
        var eventName = 'EntityService.'+type;
        var errorAttempts = 0;
        var vsite = null;

        if (Drupal.settings.spaces) {
          vsite = Drupal.settings.spaces.id;
        }

        var success = function(resp) {
          for (var i=0; i<resp.data.length; i++) {
            ents.push(resp.data[i]);
          }
          entityCount = resp.count;
          $rootScope.$broadcast(eventName+'.fetch', ents);
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
          var l = ents.length;
          for (var i =0; i < l; i++) {
            if (ents[i][prop] && ents[i][prop] == value) {
              return i;
            }
          }
        }

        this.fetch = function (params) {
          var url = restPath+'/'+entityType;
          if (!params) {
            params = {};
          }

          if (vsite) {
            params.vsite = vsite;
          }
          return $http.get(url, {params: params})
            .success(success)
            .error(errorFunc);
        }

        this.getAll = function () {
          return entities;
        }

        this.get = function (id) {
          var k = findByProp(idProp, id);
          if (ents[k]) {
            return ents[k];
          }
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
              ents.push(entity);

              $rootScope.$broadcast(eventName+'.add', entity);
            })
        };

        this.edit = function (entity) {
          if (!entity[idProp]) {
            this.add(entity);
            return;
          }
          var k = findByProp(idProp, entity[idProp]),
            url = [restPath, entityType, entity[idProp]],
            data = getDiff(ents[k], entity);

          $http.patch(url.join('/'), data)
            .success(function (resp) {
              console.log(resp);

              $rootScope.$broadcast(eventName+'.update', entity);
            })

        };

        this.delete = function (entity) {
          var k = fetchByProp(idProp, entity[idProp]);
          entities.splice(k, 1);

          //rest API call to delete entity from server


          $rootScope.$broadcast(eventName+'.delete');
        }
      }

      function getDiff(oEntity, nEntity) {
        var diff = {};

        for (var k in oEntity) {
          if (!compareProps(oEntity[k], nEntity[k])) {
            diff[k] = nEntity[k];
          }
        }

        return diff;
      }

      return factory;
    }]);

  /*
   * Buncha helper functions for getting comparisons
   */

  /**
   * Compares two properties by value.
   * If property is an array or object, recurses into them.
   * @param prop1
   * @param prop2
   * @returns {boolean}
   */
  function compareProps(prop1, prop2) {
    if (typeof prop1 != typeof prop2) {
      return false;
    }
    else if (typeof prop1 == 'object') {
      if (prop1 instanceof Array) {
        return arrayEquals(prop1, prop2);
      }
      else if (prop1 == null && prop2 == null) {
        return true;
      }
      else if (prop1 == null || prop2 == null) {
        return false;
      }
      else {
        return objectEquals(prop1, prop2);
      }
    }
    else {
      return prop1 == prop2;
    }
  }

  /**
   * Recursively compares 2 arrays by value
   * @param arr1
   * @param arr2
   * @returns {boolean}
   */
  function arrayEquals(arr1, arr2) {
    if (arr1.length != arr2.length) {
      return false;
    }
    else {
      var diff = false;
      for (var i = 0; i < arr1.length; i++) {
        diff = diff || compareProps(arr1[i], arr2[i]);
      }

      return diff;
    }
  }

  /**
   * Recursively compares 2 objects by value
   * @param obj1
   * @param obj2
   * @returns {boolean}
   */
  function objectEquals(obj1, obj2) {
    var keys1 = objectKeys(obj1),
      keys2 = objectKeys(obj2),
      diff = false;

    if (arrayEquals(keys1, keys2)) {
      for (var k in obj1) {
        diff = diff || compareProps(obj1[k], obj2[k]);
      }

      return diff;
    }
    // objects have different keys
    return false;
  }

  /**
   * Returns an array of all keys on the object
   * @param obj
   * @returns {Array}
   */
  function objectKeys(obj) {
    var keys = [];
    for (var k in obj) {
      keys.push(k);
    }
    return keys;
  }
})();