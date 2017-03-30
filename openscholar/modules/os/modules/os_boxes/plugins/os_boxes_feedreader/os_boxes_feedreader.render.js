
(function ($) {

/**
 * This script reads through feed settings and fetches feed data using the Google FeedAPI
 */
Drupal.behaviors.osBoxesFeedReader = {
  attach: function (context, settings) {
    //Loop through the feeds that are on this page
    $.each(settings.osBoxesFeedReader, function(div_id, feed_settings) {
      // run only once for each feed
      $('div#' + div_id, context).once('osBoxesFeedReader', function () {
        //Run the feed processing only once per feed
        YUI().use('yql', function (Y) {
          //Load Feed
          var query = 'select * from rss(0,' + feed_settings.num_feeds + ') where url = "' + feed_settings.url + '"';
          var q = Y.YQL(query, function (r) {
            for (var i = 0; i < r.query.results.item.length; i++) {
              var entry = r.query.results.item[i];
              var date = "";
              if (typeof entry.pubDate != 'undefined' && entry.pubDate != '') {
                if (feed_settings.time_display == 'relative') {
                  //@todo find a good way to do FuzzyTime in js
                  date = fuzzyDate(entry.pubDate);
                }

                if (feed_settings.time_display == 'formal') {
                  date = formalDate(entry.pubDate);
                }

                if (typeof date == 'undefined') {
                  date = "";
                } else {
                  date = "<span class='date'>" + date + "</span>";
                }
              }
              var content = '';
              if (feed_settings.show_content) {
                content = entry.description;
              }
              var feed_markup = "<div class='feed_item'>";

              // Put time before title if there is no content
              if (!feed_settings.show_content) {
                feed_markup = feed_markup + date;
              }

              //Add Title
              feed_markup = feed_markup + "<a class='title' href='" + entry.link + "' target='_blank'>" + entry.title + "</a>";
              if (feed_settings.show_content) {
                feed_markup = feed_markup + "<br />" + date + "<span class='description'>" + content + "<span/>";
              }
              feed_markup = feed_markup + "</div>";
              var div = $(feed_markup);

              $('div#' + div_id).append(div);
            }
          });
        });
      });
    });
  }
};

})(jQuery);


//Takes an ISO time and returns a string representing how
//long ago the date represents.
function fuzzyDate(time){
  var date = new Date(time),
    diff = (((new Date()).getTime() - date.getTime()) / 1000),
    day_diff = Math.floor(diff / 86400);
      
  if ( isNaN(day_diff) || day_diff < 0 || day_diff >= 31 ) {
    return;
  }

  return day_diff == 0 && (
    diff < 60 && "just now" ||
    diff < 120 && "1 minute ago" ||
    diff < 3600 && Math.floor( diff / 60 ) + " minutes ago" ||
    diff < 7200 && "1 hour ago" ||
    diff < 86400 && Math.floor( diff / 3600 ) + " hours ago") ||
    day_diff == 1 && "Yesterday" ||
    day_diff < 7 && day_diff + " days ago" ||
    day_diff < 31 && Math.ceil( day_diff / 7 ) + " weeks ago";
}

//Takes an ISO time and returns a string representing how
//long ago the date represents.
function formalDate(time){
  var date = new Date(time);
  var month = date.getMonth();
  var day = date.getDate();
  var year = date.getFullYear();
  var montharray=new Array("January","February","March","April","May","June", "July","August","September","October","November","December");
  return montharray[month]+" "+day+", "+year;
}