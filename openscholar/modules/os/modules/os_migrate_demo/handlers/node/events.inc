<?php

/**
 * Migrate events.
 */
class OsMigrateEventsNode extends OsMigrate {
  public $entityType = 'node';
  public $bundle = 'event';

  public $csvColumns = array(
    array('id', 'ID'),
    array('title', 'Title'),
    array('body', 'Body'),
    array('field_date', 'Timestamp'),
    array(OG_AUDIENCE_FIELD, 'Site'),
    array(OG_VOCAB_FIELD, 'Terms'),
    array('uid', 'UID'),
  );

  public $dependencies = array(
    'OsMigratePersonalNode',
    'OsMigrateProjectNode',
    'OsMigrateAuthorsTaxonomyTerm',
    'OsMigrateUsers',
  );

  public function __construct() {
    parent::__construct();

    $this->addFieldMapping('body', 'body');
    $this->addFieldMapping('field_date', 'field_date');
    $this->addFieldMapping(OG_AUDIENCE_FIELD, OG_AUDIENCE_FIELD)
      ->sourceMigration(array('OsMigratePersonalNode', 'OsMigrateProjectNode'));
    $this->addFieldMapping(OG_VOCAB_FIELD, OG_VOCAB_FIELD)
      ->sourceMigration(array('OsMigrateAuthorsTaxonomyTerm'))
      ->separator('|');

    $this->addFieldMapping('uid', 'uid')
      ->sourceMigration('OsMigrateUsers');
  }

  public function prepare($entity, $row) {
    $date = explode(';', $row->field_date);

    if ($date[0] == 'now') {
      $date[0] = date('Y-m-d H:i', time());
    }

    $entity->field_date[LANGUAGE_NONE][0] = array(
      'value' => $date[0],
      'date_type' => 'datetime',
    );

    // Adding the end date of the event.
    if (isset($date[1])) {
      if ($date[1] == 'now') {
        $date[1] = date('Y-m-d H:i', time());
      }

      $entity->field_date[LANGUAGE_NONE][0]['value2'] = $date[1];
    }
  }
}
