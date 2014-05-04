<?php

/**
 * Migrating plugins.
 */
class MigrateBoxes extends Migration {
  public $csvColumns = array(
    array('id', 'Unique ID'),
    array('title', 'Tile'),
    array('description', 'Description'),
    array('page', 'Page'),
    array('location', 'Location'),
    array('delta', 'Delta'),
  );

  /**
   * Constructing the class.
   */
  public function __construct() {
    parent::__construct();
    $this->description = t('Import plugins.');

    $this->addFieldMapping('title', 'name');
    $this->addFieldMapping('description', 'pass');
    $this->addFieldMapping('page', 'mail');
    $this->addFieldMapping('location', 'first_name');
    $this->addFieldMapping('delta', 'last_name');

    // Create a map object for tracking the relationships between source rows
    $key = array(
      'id' => array(
        'type' => 'varchar',
        'length' => 255,
        'not null' => TRUE,
      ),
    );

    $destination_handler = new MigrateDestinationUser();
    $this->map = new MigrateSQLMap($this->machineName, $key, $destination_handler->getKeySchema());

    // Create a MigrateSource object.
    $this->source = new MigrateSourceCSV(drupal_get_path('module', 'os_migrate_demo') . '/csv/boxes/plugins.csv', $this->csvColumns, array('header_rows' => 1));
    $this->destination = new MigrateDestinationUser();
  }
}
