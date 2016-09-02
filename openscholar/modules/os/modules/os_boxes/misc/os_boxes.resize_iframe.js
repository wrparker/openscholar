jQuery(document).ready(function(){
  jQuery.each(Drupal.settings.widget_max_width, function(wrapper_id, width){
    var iframe = jQuery('#' + wrapper_id + ' iframe');
    jQuery(iframe).attr('width', width); 
    jQuery(iframe).attr('scrolling', 'auto'); 
  });
});