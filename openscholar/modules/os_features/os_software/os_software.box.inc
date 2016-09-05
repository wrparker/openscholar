<?php
/**
 * @file
 * os_software.box.inc
 */

/**
 * Implements hook_default_box().
 */
function os_software_default_box() {
  $export = array();

  $box = new stdClass();
  $box->disabled = FALSE; /* Edit this to true to make a default box disabled initially */
  $box->api_version = 1;
  $box->delta = 'os_software_releases-block_1';
  $box->plugin_key = 'os_sv_list_box';
  $box->title = 'Recent Software Releases';
  $box->description = 'Recent Software Releases';
  $box->options = array(
    'content_type' => 'software_release',
    'display' => 'title',
    'more' => 1,
    'more_text' => 'More',
    'more_link' => 'software',
    'pager' => 0,
    'number_of_items' => '6',
    'sort_by' => 'sort_newest',
  );
  $export['os_software_releases-block_1'] = $box;

  return $export;
}
