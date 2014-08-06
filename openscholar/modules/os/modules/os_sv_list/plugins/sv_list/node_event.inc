<?php

/**
 * @file node_event.inc
 *
 * event plugin for sv_list box
 */

$plugin = array(
  'handler' => array('class' => 'sv_list_node_event'),
  'entity_type' => array('node'),
  'bundle' => array('event'),
);

class sv_list_node_event extends sv_list_plugin  {
  /**
   * @function register_sorts()
   *
   * Adds a custom sort for this entity/bundle
   */
  public function register_sorts() {
    return array('sort_event' => t('Event Date'));
  }

  /**
   * @function sort_event
   *
   * Custom sort for events.  Uses event date, but only for
   * upcoming events that haven't taken place yet.
   */
  public function sort_event($options, EntityFieldQuery $efq) {
    $efq->addTag('sv_list_events');
    $efq->fieldOrderBy('field_date', 'value', 'ASC');
    $efq->fieldCondition('field_date', 'value', gmdate('Y-m-d H:i:s', strtotime('-30 minutes')), '>=', 'sv_list_events');
    $efq->fieldCondition('field_date', 'value2', gmdate('Y-m-d H:i:s'), '>=', 'sv_list_events');

    $this->query = $efq;
  }

  /**
   * @function entities_alter
   *
   * Events have been loaded, but repeated events are grouped under a single node.
   * Split them into several nodes, then re-sort and slice them.
   */
  public function entities_alter($options, &$entities) {
    if ($options['content_type'] != 'event' || $options['sort_by'] != 'sort_event') {
      return;
    }

    // The EntityFieldQuery has the raw DB data on it's ordered_results property
    // We earlier modified this query to give a separate row for each nid-delta
    // So we need to add a value to the entities array for each row in the raw DB
    // Every entity we need should already be in the entities array
    $output = array();
    $raw = $this->query->ordered_results;
    foreach ($raw as $row) {
      if (isset($entities[$row->entity_id])) {
        $entity = clone $entities[$row->entity_id];
        $entity->date_id = implode('.', array('date', $entity->nid, 'field_date', $row->delta));
        $output[] = $entity;
      }
    }

    $entities = $output;
  }

  /**
   * @function render
   *
   * The default entity_view() render function loads new copies of the entities.
   * We can't allow that because it'll invalidate entities_alter().  Instead,
   * just call node_view on each of them.
   */
  public function render($options, &$entities) {
    if ($options['content_type'] != 'event' || $options['sort_by'] != 'sort_event') {
      return;
    }

    $render = array();
    foreach ($entities as $node) {
      $render[] = node_view($node, $options['display']);
    }

    return $render;
  }

  /**
   * Set time cache based on options by a time string.
   *
   * @param $options
   *   Box options.
   * @param $cache_time
   *   Cache time string.
   */
  public function set_cache_time($options, &$cache_time) {
    if ($options['content_type'] == 'event' && $options['sort_by'] == 'sort_event') {
      $cache_time = "5 minutes";
    }
  }
}
