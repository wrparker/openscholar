/**
 * Provides a generic module for interacting with entities via our REST API
 */
(function () {

  var restPath = '',
    entities = {},
    fetched = {};

  angular.module('EntityService', [])
    .config(function () {
      restPath = Drupal.settings.paths.api;
    })
  /**
   * Service to maintain the list of files on a user's site
   */
    .factory('EntityService', ['$rootScope', '$http', '$q', function ($rootScope, $http, $q) {
      var factory = function (entityType, idProp) {
        var type = entityType;
        var ents;
        entities[entityType] = ents = entities[entityType] || [];
        var entityCount = 0;
        var eventName = 'EntityService.' + type;
        var errorAttempts = 0;
        var vsite = null;
        var fetchDefer;

        if (Drupal.settings.spaces) {
          vsite = Drupal.settings.spaces.id;
        }

        var success = function(resp) {
          ents.length = 0;

          recursiveFetch(resp);

          fetched[type] = true;
          entityCount = resp.count;
        }

        function recursiveFetch(resp) {
          for (var i=0; i<resp.data.length; i++) {
            ents.push(resp.data[i]);
          }

          if (resp.next) {
            var max = Math.ceil(resp.count/resp.data.length),
              curr = resp.next.href.match(/page=([\d])/)[1];
            fetchDefer.notify(("Loading $p% complete.").replace('$p', ((curr-1)/max)*100));
            $http.get(resp.next.href).success(recursiveFetch);
          }
          else {
            fetchDefer.resolve(ents);
            $rootScope.$broadcast(eventName+'.fetch', ents);
          }
        }

        var errorFunc = function() {
          errorAttempts++;
          if (errorAttempts < 3) {
            $http.get({url: restPath}).
              success(success).
              error(errorFunc);
          }
          else {
            fetchDefer.reject('Error getting files. Aborting after 3 attempts.');
          }
        };

        function findByProp(prop, value) {
          var l = ents.length;
          for (var i =0; i < l; i++) {
            if (ents[i][prop] && ents[i][prop] == value) {
              return i;
            }
          }
        }

        this.fetch = function (params) {
          if (!fetchDefer) {
            var url = restPath + '/' + entityType;
            if (!params) {
              params = {};
            }

            if (vsite) {
              params.vsite = vsite;
            }
            fetchDefer = $q.defer();
            $http.get(url, {params: params})
              .success(success)
              .error(errorFunc);
            setTimeout(function () {
              fetchDefer.notify("Loading 0% complete.");
            }, 0);
          }
          return fetchDefer.promise;
        }

        this.getAll = function () {
          return ents;
        }

        this.get = function (id) {
          var k = findByProp(idProp, id);
          if (ents[k]) {
            return ents[k];
          }
        };

        this.getCount = function () {
          return entityCount;
        };

        this.add = function (entity) {
          var k = findByProp(idProp, entity[idProp]);
          if (entities[k]) {
            throw new Exception('Cannot add entity of type ' + type + ' that already exists.');
          }

          if (vsite) {
            entity.vsite = vsite;
          }

          // rest API call to add entity to server
          return $http.post(restPath + '/' + entityType, entity)
            .success(function (resp) {
              var entity = resp.data[0];
              ents.push(entity);

              $rootScope.$broadcast(eventName + '.add', entity);
            })
        };

        this.edit = function (entity, ignore) {
          if (!entity[idProp]) {
            this.add(entity);
            return;
          }
          ignore = ignore || [];
          ignore.push(idProp);

          var k = findByProp(idProp, entity[idProp]),
            url = [restPath, entityType, entity[idProp]],
            data = getDiff(ents[k], entity, ignore);

          if (data.length) {
            delete data.length;

            return $http.patch(url.join('/'), data)
              .success(function (resp) {
                var entity = resp.data[0];

                ents.splice(k, 1, entity);

                $rootScope.$broadcast(eventName + '.update', entity);
              });
          }
          else {
            var defer = $q.defer();
            setTimeout(function () {
              defer.resolve(false);
            }, 1);
            return defer.promise;
          }
        };

        this.delete = function (entity) {

          //rest API call to delete entity from server
          return $http.delete(restPath+'/'+entityType+'/'+entity[idProp]).success(function (resp) {
            var k = findByProp(idProp, entity[idProp]);
            ents.splice(k, 1);

            $rootScope.$broadcast(eventName+'.delete', entity[idProp]);
          });
        }

        // registers an entity with this service
        // used for entities that are added outside of this service
        this.register = function (entity) {
          ents.push(entity);
        }
      };

      function getDiff(oEntity, nEntity, ignore) {
        var diff = {},
          numProps = 0;

        for (var k in oEntity) {
          if (ignore.indexOf(k) == -1 && !compareProps(oEntity[k], nEntity[k])) {
            diff[k] = nEntity[k];
            numProps++;
          }
        }
        diff.length = numProps;

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
    if (typeof prop1 == 'object') {
      if (prop1 instanceof Array && prop2 instanceof Array) {
        return arrayEquals(prop1, prop2);
      }
      else if (prop1 == null && prop2 == null) {
        return true;
      }
      // rest apis return 'null' instead of an empty array
      // some widgets convert that into an empty array, and thus we need to check here
      else if (prop1 == null && prop2 instanceof Array && prop2.length == 0) {
        return true;
      }
      else if (prop2 == null && prop1 instanceof Array && prop1.length == 0) {
        return true;
      }
      // if neither property is an array, return false as usual
      else if (prop1 == null || prop2 == null) {
        return false;
      }
      else {
        return objectEquals(prop1, prop2);
      }
    }
    else if (typeof(prop1) == typeof(prop2)) {
      return prop1 == prop2;
    }
    else {
      return prop1.toString() == prop2.toString();
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
