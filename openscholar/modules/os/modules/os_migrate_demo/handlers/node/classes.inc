<?php

/**
 * Migrating classes.
 */
class OsMigrateClassesNode extends OsMigrate {
  public $entityType = 'node';
  public $bundle = 'class';

  public $csvColumns = array(
    array('id', 'ID'),
    array('title', 'Title'),
    array('body', 'Body'),
    array('link', 'Link'),
    array('semester', 'Semester'),
    array('field_offered_year', 'Year'),
    array(OG_AUDIENCE_FIELD, 'Site'),
    array(OG_VOCAB_FIELD, 'Terms'),
    array('path', 'Path'),
    array('uid', 'UID'),
  );

  public $dependencies = array(
    'OsMigratePersonalNode',
    'OsMigrateProjectNode',
    'OsMigrateScienceTaxonomyTerm',
    'OsMigrateUsers',
  );

  public function __construct() {
    parent::__construct();

    $this->addFieldMapping('body', 'body');
    $this->addFieldMapping('field_semester', 'semester');
    $this->addFieldMapping('field_offered_year', 'field_offered_year');

    $this->addFieldMapping(OG_AUDIENCE_FIELD, OG_AUDIENCE_FIELD)
      ->sourceMigration(array('OsMigrateProjectNode', 'OsMigratePersonalNode'));

    $this->addFieldMapping(OG_VOCAB_FIELD, OG_VOCAB_FIELD)
      ->sourceMigration(array('OsMigrateScienceTaxonomyTerm'))
      ->separator('|');

    $this->addFieldMapping('path', 'path');

    $this->addFieldMapping('uid', 'uid')
      ->sourceMigration('OsMigrateUsers');
  }

  /**
   * Dealing with the link url field.
   */
  public function prepare($entity, $row) {
    $link = explode(";", $row->link);

    $entity->field_class_link[LANGUAGE_NONE][0]['url'] = $link[0];
    $entity->field_class_link[LANGUAGE_NONE][0]['title'] = $link[1];

    $entity->field_offered_year[LANGUAGE_NONE][0] = array(
      'value' => $row->field_offered_year . '-01-01 00:00:00',
      'timezone' => date_default_timezone(),
      'timezone_db' => date_default_timezone(),
      'date_type' => 'datetime',
    );

    if (!empty($row->path)) {
      // Disable path-auto.
      $entity->path['pathauto'] = FALSE;
    }
  }
}
