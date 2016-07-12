/**
 * @file os_boxes_widget_slider.js
 * 
 * Adjusts div container's height for widget sliders.
 */

(function ($) {

// Behavior to load responsiveslides
Drupal.behaviors.os_widget_slider = {
  attach: function(context, settings) {
    $('.block-boxes-os_boxes_widget_slider').each(function(i){
      var maxHeight = 0;
      $(this).find('ul.rslides li').each(function(j){
        if ($(this).height() != 0) {
          maxHeight = (maxHeight < $(this).height()) ? $(this).height() : maxHeight;
        }
      });
      $(this).find('.rslides li').css({'height':maxHeight, 'overflow' : 'auto'});
      $(this).find('.slidecontrol').css('margin-top','50px');
    });
  }
}
}(jQuery));
