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
    array('parent', 'Parent'),
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

  public function prepare($entity, $row) {
    $entity->book = array(
      'bid' => 'new',
    );
  }

  public function complete($entity, $row) {
    if (!$row->parent) {
      return;
    }

    // Get the parent node by title (given in the CSV).
    $query = new entityFieldQuery();
    $result = $query
      ->entityCondition('entity_type', 'node')
      ->propertyCondition('title', $row->parent)
      ->execute();
    $parent = node_load(reset($result['node'])->nid);

    // Make the current entity the child of the parent.
    $entity->book['bid'] = $parent->book['bid'];
    $entity->book['depth'] += 1;
    $entity->book['menu_name'] = $parent->book['menu_name'];
    $entity->book['p2'] = $entity->book['p1'];
    $entity->book['p1'] = $entity->book['plid'];

    entity_save('node', $entity);
  }
}
