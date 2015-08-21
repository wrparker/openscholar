jQuery(document).ready(function() {
	var num = 184; //number of pixels before modifying styles

jQuery(window).bind('scroll', function () {
    if (jQuery(window).scrollTop() > num) {
        jQuery('#menu-bar').addClass('fixed');
    } else {
        jQuery('#menu-bar').removeClass('fixed');
    }
});
}); 