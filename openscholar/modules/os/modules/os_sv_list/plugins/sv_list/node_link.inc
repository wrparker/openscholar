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
   */
  public function render($options, &$entities) {
    if ($options['display'] != 'full') {
      return;
    }
    
    foreach ($entities as $key => $entity) {
      $markup = $entities[$key]->field_links_link[LANGUAGE_NONE][0]['url'];
      $entities[$key]->field_links_link[LANGUAGE_NONE][0]['value'] = $markup;
      $entities[$key]->field_links_link[LANGUAGE_NONE][0]['url'] = NULL;
    }
  }
}
