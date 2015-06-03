<?php

/**
 * Migrate "Biography" node.
 */
class OsMigrateBiographyNode extends OsMigrate {
  public $entityType = 'node';
  public $bundle = 'bio';

  public $csvColumns = array(
    array('id', 'ID'),
    array('title', 'Title'),
    array('body', 'Body'),
    array(OG_AUDIENCE_FIELD, 'Site'),
    array('uid', 'UID'),
    array('files', 'Files'),
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

    $this->addFieldMapping('uid', 'uid')
      ->sourceMigration('OsMigrateUsers');

    $this->addFieldMapping('field_upload', 'files')
      ->separator(';');
    $this->addFieldMapping('field_upload:file_replace')
      ->defaultValue(FILE_EXISTS_REPLACE);
    $this->addFieldMapping('field_upload:source_dir')
      ->defaultValue(drupal_get_path('module', 'os_migrate_demo') . '/images');
    $this->addFieldMapping('field_upload:destination_dir', 'destination');
  }

  /**
   * Create OG memberships for the used files.
   */
  public function complete($entity, $row) {
    $wrapper = entity_metadata_wrapper('node', $entity);
    foreach ($wrapper->field_upload->value() as $file) {
      $gid = $wrapper->{OG_AUDIENCE_FIELD}->value(array('identifier' => TRUE));
      $membership = og_membership_create('node', reset($gid), 'file', $file['fid'], OG_AUDIENCE_FIELD);
      og_membership_save($membership);
    }
  }
}
