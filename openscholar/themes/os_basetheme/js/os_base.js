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
	
	jQuery(".block-boxes-os_search_solr_search_box").addClass("block-os-search-solr");

    jQuery("figure img").each(function() {
        var imgwidth = jQuery(this).attr('width');
        jQuery(this).parent().find('figcaption').css({
            "width" : + imgwidth
        });
    });
});

function template_download_information(val) {
 jQuery('.import-help').addClass('element-hidden');
  if (val == 'csv') {
    jQuery('.csv-import-fields').removeClass('element-hidden');
    jQuery('.rss-import-fields').addClass('element-hidden');
    //jQuery('span[name^="os_importer_encode-"]').parent().removeClass('element-hidden');
    //jQuery('div[id^="edit-os_importer_file_upload_"]').removeClass('element-hidden');
  } else if (val == 'rss') {
    jQuery('.rss-import-fields').removeClass('element-hidden');
    jQuery('.csv-import-fields').addClass('element-hidden');
    //jQuery('span[name^="os_importer_encode-"]').parent().addClass('element-hidden');
    //jQuery('div[id^="edit-os_importer_file_upload_"]').addClass('element-hidden');
  }
}