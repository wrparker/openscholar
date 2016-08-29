<?php

/**
 * @file
 * OS importer custom node importer. Supply support for importing files from
 * external source.
 */

class OsImporterNodeProcessor extends FeedsNodeProcessor {
  /**
   * Override parent::configDefaults().
   */
  public function configDefaults() {
    return array(
      'file_field' => NULL,
    ) + parent::configDefaults();
  }

  /**
   * Override parent::configForm().
   */
  public function configForm(&$form_state) {
    $form = parent::configForm($form_state);
    $mappings = $this->getMappings();

    // Check first that there is target of files.
    $file = FALSE;
    foreach ($mappings as $map) {
      if ($map['target'] == 'file') {
        $file = TRUE;
      }
    }

    // The user did not map any file source or did not select a bundle.
    if (!$file || !$this->config['content_type']) {
      return $form;
    }

    $fields_info = field_info_instances('node', $this->config['content_type']);

    $field = array();
    foreach ($fields_info as $name => $info) {
      $field[$name] = $info['label'];
    }

    $form['file_field'] = array(
      '#type' => 'select',
      '#title' => t('File field'),
      '#description' => t('Select the source file field for holding the files.'),
      '#options' => $field,
      '#default_value' => $this->config['file_field']
    );

    return $form;
  }

  /**
   * Return available mapping targets.
   */
  public function getMappingTargets() {
    self::loadMappers();
    $targets = parent::getMappingTargets();
    $entity_type = $this->entityType();
    $bundle = $this->config['content_type'];

    $targets['file'] = array(
      'name' => t('Files'),
      'description' => t('Import files from url into the field. Saturated by |'),
      'callback' => 'os_importer_map_files',
    );

    if ($bundle == 'event') {
      $targets['registration'] = array(
        'name' => t('Registration'),
        'description' => t('Enable the registration for the event.'),
        'callback' => 'os_importer_event_registration',
      );
    }

    // Let other modules expose mapping targets.
    drupal_alter('feeds_processor_targets', $targets, $entity_type, $bundle);

    return $targets;
  }

  /**
   * Save a node.
   * Override parent::entitySave().
   */
  public function entitySave($entity) {
    // If nid is set and a node with that id doesn't exist, flag as new.
    if (!empty($entity->nid) && !node_load($entity->nid)) {
      $entity->is_new = TRUE;
    }

    $batch = &batch_get();

    try {
      node_save($entity);
      $batch['sets'][0]['results']['nodes_inserted'][] = $entity->nid;
    }
    catch (Exception $e) {
      $batch['sets'][0]['results']['node_save_failed'] = TRUE;
      $batch['sets'][0]['results']['node_save_failed_message'] =
          ($e->getMessage()) ? $e->getMessage() : "No exception message available";
    }
  }
}
