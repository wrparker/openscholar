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
   */
  public function render($options, &$entities) {
    if ($options['display'] != 'full') {
      return;
    }

    foreach ($entities as $entity) {
      $entity->sv_list = TRUE;
    }
  }
}
