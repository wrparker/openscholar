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
    // every entity we need should already be in the entities array
    $output = array();
    $raw = $this->query->ordered_results;
    dpm($raw);
    foreach ($raw as $row) {
      $entity = clone $entities[$row->entity_id];
      $entity->date_id = implode('.', array('date', $entity->nid, 'field_date', $row->delta));
      $output[] = $entity;
    }
    dpm($output);

    $entities = $output;
/*
    // Split repeated event into many nodes - one for each instance of event
    // key them by timestamp for easy sorting.
    $exploded = array();
    $now = REQUEST_TIME;
    foreach ($entities as $entity) {
      foreach ($entity->field_date[LANGUAGE_NONE] as $delta => $date) {
        $time_index = strtotime($date['value']);
        while (array_key_exists($time_index, $exploded)) {
          $time_index += 1; //don't clobber events that start at same time.
        }

        // The request time is calculated in the UTC timezone.
        // In order to compare it against the event time values we need
        // get the timestamp of the event's start and end times to UTC as well.
        $utc_time = new DateTime($date['value'], new DateTimeZone('UTC'));
        $local_start_time = $utc_time->getTimestamp();
        $utc_time = new DateTime($date['value2'], new DateTimeZone('UTC'));
        $local_end_time = $utc_time->getTimestamp();
        if ($local_end_time >= $now) {
          // Remove the events which their starting date is less then half hour
          // ago.
          if ($this->VerifyStartDate($now, $local_start_time)) {
            continue;
          }

          $new = clone $entity;
          $new->field_date[LANGUAGE_NONE][0] = $date;
          $new->date_id = implode('.', array('date', $new->nid, 'field_date', $delta));
          $exploded[$time_index] = $new;
        }
      }
    }

    ksort($exploded);    //now sort by timestamp

    // filter by page num
    // When making normal requests, the page is always 0. We don't store the page the user is on between requests.
    // When getting the page with AJAX, this box is the only pager active. Therefore, we want the 0 pager element.
    $page_num = pager_find_page();
    $limit = $options['number_of_items'];
    $entities = array_slice(array_values($exploded), $page_num * $limit, $limit);  //and re-apply the item limit*/
  }

  /**
   * Verify the start date accouter no longer then 30 minutes ago.
   */
  protected function VerifyStartDate($now, $start_date) {
    return ($now - (30 * 60)) >= $start_date;
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
