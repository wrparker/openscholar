<?php
/**
 * @file
 * field template for the main photo associated with a news item
 *
 */
?>

<div class="<?php print $classes ?>" <?php print $attributes ?>>

<?php if(!$label_hidden): ?>
    <h2 class="field-label"<?php print $title_attributes ?>><?php print $label ?></h2>
<?php endif; ?>

	<div class="field-items" <?php print $content_attributes?>>
<?php foreach($items as $delta => $item): ?>
	<?php $classes = 'field-item ' . ($delta % 2 ? 'odd' : 'even'); ?>
    	<figure class="clearfix <?php print $classes ?>" <?php print $item_attributes[$delta]?>>
    <?php print drupal_render($item); ?>

    <?php if (isset($item['#item']['os_file_description']) && variable_get('os_news_enable_photo_caption', FALSE) && $variables['field_view_mode'] == 'full') : ?>
      <?php $styles = get_themed_image_width($item) ? 'style="width:' . get_themed_image_width($item) . 'px"' : ''; ?>
			<figcaption class="caption full-caption"<?php print $styles ?>><?php print $item['#item']['os_file_description']['und'][0]['value'] ?></figcaption>
	<?php endif; ?>
    	</figure>
<?php endforeach; ?>
	</div>
</div>

<?php
/**
 * Helper function to get width of image
 */
if (!function_exists('get_themed_image_width')) {
  function get_themed_image_width ($image = null) {
	if ($image) {
		  // let's see if there's image style info we can use
		  $image_style = image_style_load($image['#image_style']);
		  $width = "";
		  foreach ($image_style['effects'] as $delta => $effect) {
			if (isset($effect['data']['width'])) {
			  $width = $effect['data']['width'];
			}
		  }

		  // otherwise, grab it from the rendered HTML
		  if (!$width) {
			  preg_match('/< *img[^>]*width *= *["\']?([^"\']*)/i', $image['#children'], $matches);
			  $width = $matches[1];
		  }
		  return $width;
		}
	}
}
?>
