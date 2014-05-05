<?php

/**
 * Migrate "Page" node.
 */
class OsMigratePageNode extends OsMigrate {
  public $entityType = 'node';
  public $bundle = 'page';

  public $csvColumns = array(
    array('id', 'ID'),
    array('title', 'Title'),
    array('body', 'Body'),
    array(OG_AUDIENCE_FIELD, 'Site'),
    array('files', 'Files'),
    array('uid', 'UID'),
  );

  public $dependencies = array(
    'OsMigratePersonalNode',
    'OsMigrateProjectNode',
    'OsMigrateUsers',
  );

  public function __construct() {
    parent::__construct();

    $this->addFieldMapping('body', 'body');
    $this->addFieldMapping(OG_AUDIENCE_FIELD, OG_AUDIENCE_FIELD)
      ->sourceMigration(array('OsMigratePersonalNode', 'OsMigrateProjectNode'));

    $this->addFieldMapping('field_upload', 'files')
      ->separator(';');
    $this->addFieldMapping('field_upload:file_replace')
      ->defaultValue(FILE_EXISTS_REPLACE);
    $this->addFieldMapping('field_upload:source_dir')
      ->defaultValue(drupal_get_path('module', 'os_migrate_demo') . '/images');

    $this->addFieldMapping('uid', 'uid')
      ->sourceMigration('OsMigrateUsers');
  }
}
