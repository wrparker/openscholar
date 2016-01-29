// Fixes tab behavior after using the skip link in IE or Chrome
Drupal.behaviors.osBase_skipLinkFocus = {
	attach : function(ctx) {
		var $ = jQuery, $skip = $('#skip-link a', ctx);

		$skip.click(function(e) {
			var target = $skip[0].hash.replace('#', '');

			setTimeout(function() {
				$('a[name="' + target + '"]').attr('tabindex', -1).focus();
			}, 100);
		});
	}
};

jQuery(document).ready(function() {
	jQuery(".cal-export").click(function() {
		jQuery(".attachment.attachment-before ul").slideToggle();
		jQuery(".os_events_export_links .last").slideToggle();

	});

	jQuery("figure img.media-element").each(function() {

		var imgwidth = jQuery("figure img.media-element").attr('width');
		var imgheight = jQuery("figure img.media-element").attr('height');
		// Adjusting figure images css height property same as height attribute value of img tag.
        jQuery("figure img.media-element").css({
        	"height" : + imgheight
        });
		jQuery("figure img.media-element ~ figcaption").css({
			"width" : +imgwidth
		});
	});

});

