<?php
/**
 * @file
 * os_dataverse.box.inc
 */

/**
 * Implements hook_default_box().
 */
function os_dataverse_default_box() {
  $export = array();

  $box = new stdClass();
  $box->disabled = FALSE; /* Edit this to true to make a default box disabled initially */
  $box->api_version = 1;
  $box->delta = 'os_dataverse_search_box';
  $box->plugin_key = 'os_boxes_dataverse_search';
  $box->title = 'Dataverse Search Box';
  $box->description = 'Add a way for visitors on your website to be able to search Dataverse.';
  $box->options = array(
    'visibility' => 'feature',
    'widget_type' => 'toolbox_small',
  );
  $export['os_dataverse_search_box'] = $box;

  return $export;
}
