<?php

/**
 * @file node_faq.inc
 *
 * faq plugin for sv_list box
 */

$plugin = array(
  'handler' => array('class' => 'sv_list_node_faq'),
  'entity_type' => array('node'),
  'bundle' => array('faq'),
);

class sv_list_node_faq extends sv_list_plugin  {

  /**
   * Add assets related only to the current bundle presented.
   */
  public function add_cache_assets($box, &$assets) {
    $assets['js'][] = drupal_get_path('module', 'os') . '/theme/os_toggle.js';
  }
}
