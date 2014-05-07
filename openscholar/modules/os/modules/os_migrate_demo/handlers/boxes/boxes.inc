<?php

/**
 * Migrating plugins.
 */
class MigrateBoxes extends Migration {

  public $csvColumns = array(
    array('id', 'ID'),
    array('title', 'Tile'),
    array('description', 'Description'),
    array('page', 'Page'),
    array('location', 'Location'),
    array('delta', 'Delta'),
    array(OG_AUDIENCE_FIELD, 'Site'),
  );

  public $dependencies = array(
    'OsMigratePersonalNode',
    'OsMigrateProjectNode',
  );

  /**
   * Constructing the class.
   */
  public function __construct() {
    parent::__construct();
    $this->description = t('Import plugins.');

    $this->addFieldMapping('title', 'title');
    $this->addFieldMapping('description', 'description');
    $this->addFieldMapping('page', 'page');
    $this->addFieldMapping('location', 'location');
    $this->addFieldMapping('delta', 'delta');
    $this->addFieldMapping(OG_AUDIENCE_FIELD, OG_AUDIENCE_FIELD)
      ->sourceMigration(array('OsMigratePersonalNode', 'OsMigrateProjectNode'));

    // Create a map object for tracking the relationships between source rows
    $key = array(
      'id' => array(
        'type' => 'varchar',
        'length' => 255,
        'not null' => TRUE,
      ),
    );

    $destination_handler = new SpacesOverridesDestination();
    $this->map = new MigrateSQLMap($this->machineName, $key, $destination_handler->getKeySchema());

    // Create a MigrateSource object.
    $this->source = new MigrateSourceCSV(drupal_get_path('module', 'os_migrate_demo') . '/csv/boxes/boxes.csv', $this->csvColumns, array('header_rows' => 1));
    $this->destination = $destination_handler;
  }

  /**
   * OsBoxesAddthis settings callback.
   */
  function OsBoxesAddthisSettings() {
    return array('widget_type' => 'toolbox_small');
  }
}
