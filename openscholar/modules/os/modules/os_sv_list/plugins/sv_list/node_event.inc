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
    $efq->fieldOrderBy('field_date', 'value', 'DESC');
    $efq->fieldCondition('field_date', 'value2', date('Y-m-d H:i:s'), '>=');
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

    // Split repeated event into many nodes - one for each instance of event
    // key them by timestamp for easy sorting.
    $exploded = array();
    $now = strtotime(date('Y-m-d H:i:s'));
    foreach ($entities as $entity) {
      foreach ($entity->field_date[LANGUAGE_NONE] as $delta => $date) {

        $end_date = new DateTime($date['value2'], new DateTimeZone($date['timezone_db']));
        $end_date->setTimezone(new DateTimeZone(date_default_timezone()));

        $time_index = strtotime($date['value']);
        while (array_key_exists($time_index, $exploded)) {
          $time_index += 1; //don't clobber events that start at same time.
        }

        if ($end_date->getTimestamp() >= $now) {
          // Remove the events which their starting date is less then half hour
          // ago.
          if ($this->VerifyStartDate($now, $date['value'])) {
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
    $entities = array_slice(array_values($exploded), 0, $options['number_of_items']);  //and re-apply the item limit
  }

  /**
   * Verify the start date accouter no longer then 30 minutes ago.
   */
  protected function VerifyStartDate($now, $start_date) {
    $time_zone = date_default_timezone();

    // Get the string of the date and define it as UTC time.
    $date = new DateTime($start_date, new DateTimeZone('UTC'));

    // Affect the time object according to the current time zone.
    $date->setTimezone(new DateTimeZone($time_zone));

    // Check if the event start date was no longer then 30 minutes ago.
    return ($now - (30 * 60)) >= $date->getTimestamp();
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
}
