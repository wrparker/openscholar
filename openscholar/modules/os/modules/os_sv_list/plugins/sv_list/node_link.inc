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
}
