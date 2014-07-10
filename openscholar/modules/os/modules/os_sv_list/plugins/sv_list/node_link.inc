<?php

/**
 * @file node_link.inc
 *
 * link plugin for sv_list link
 */

$plugin = array(
  'handler' => array('class' => 'sv_list_node_link'),
  'entity_type' => array('node'),
  'bundle' => array('link'),
);

class sv_list_node_link  extends sv_list_plugin {

  /**
   * Overrides sv_list_plugin::render().
   *
   * In the LOP with full view mode don't display the URL as a link.
   * We give it a class to style it appropriately.
   */
  public function render($options, &$entities) {
    if ($options['display'] != 'full') {
      return;
    }

    foreach ($entities as &$entity) {
      $entity->field_links_link[LANGUAGE_NONE][0]['attributes']['class'] = 'disable-link-lop';
    }
  }
}
