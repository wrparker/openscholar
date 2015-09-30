(function () {
  var rootPath;

    angular.module('UserPanel', ['AdminPanel'])
    .config(function (){
       var rootPath = Drupal.settings.paths.adminPanelModuleRoot;
       var restPath = Drupal.settings.paths.api;
       
       if(typeof(Drupal.settings.spaces) == 'undefined') {
    	 var vsite = false;   
       } else {	   
         var vsite = Drupal.settings.spaces.id;
       }
       
       var user_data = Drupal.settings.user_panel.user;
       var paths = Drupal.settings.paths;
       var notify_settings = Drupal.settings.os_notifications;
    }).controller("UserMenuController",['$scope', '$http', function ($scope, $http) {
    
      $scope.user = user_data;
      $scope.vsite = { id: vsite };
      $scope.paths = paths;
     
    }]).directive('rightMenu', function() {
      return {
       templateUrl: rootPath+'/templates/user_menu.html?vers='+Drupal.settings.version.userPanel,
       controller: 'UserMenuController',
     };
    }).directive('addDestination', function() {
      //Add the destination to a URL
	  return {
	    link: function(scope, element, attrs) {
		  var url = (typeof(attrs.href) == 'undefined') ? attrs.ngHref : attrs.href;
	      element.attr('href', url + ((url.indexOf('?') == -1) ? '?' : '&') + 'destination=' + encodeURIComponent(location.href));
  	    },
	  }
    }).directive('notificationCheck', function() {
      //check for google and hopscotch
      if (typeof google == 'undefined' || typeof hopscotch == 'undefined') {
        return {};
	  }
      
      //Go to feed and check for notifications.
  	  return {
  	    link: function(scope, element, attrs) {
  		  count_element = angular.element('<span class="slate alert" ng-alert=></span>');
  		  // @TODO: Add support for multiple feeds.
	      var feed = new google.feeds.Feed(notify_settings.url);
	      var items = [];
	      var link = element.find('a')[0];
	      
	      feed.setNumEntries(notify_settings.max);
	      feed.load(function (result) {
	        if (result.error) {
	          return;
	        }
	          
	        for (var i = 0; i < result.feed.entries.length; i++) {
	          var num_remaining = (result.feed.entries.length - i);
	          var entry = result.feed.entries[i];
	          if (osTour.notifications_is_new(entry)) {
	            var item = osTour.notifications_item(entry, num_remaining, count_element, link);
	            items.push(item);
	          }
	        }

	        // If there are new items
	        if (items.length) {
	          // Sets up the DOM elements.
	          element.append(count_element);
	          osTour.notifications_count(count_element, items.length);
	            //$('#os-tour-notifications-menu-link').slideDown('slow');
	          
	          // Sets up the tour object with the loaded feed item steps.
	          var tour = {
	            showPrevButton: true,
	            scrollTopMargin: 100,
	            id: "os-tour-notifications",
	            steps: items,
	            onEnd: function() {
	              osTour.notifications_count(-1, count_element);
	              osTour.notifications_read_update();
	            }
	          };

	          element.find('a').bind('click', function(e) {
        	    e.preventDefault();
        	    hopscotch.startTour(tour);
              })
	        }
	      });
    	},
  	  }
    }).directive('rightMenuToggle', function() {
        return {
            link: function(scope, element, attrs) {
        	  element.bind('click', function(e) {
        		  e.preventDefault();
        		  element.siblings('div').each( function () {
        	        if(this.style.display == 'none') {
        	          jQuery(this).slideDown();	
        	        } else {
        	          jQuery(this).slideUp();
        	        }
        	      });
              })  
            },
          }
        });
  
})();
