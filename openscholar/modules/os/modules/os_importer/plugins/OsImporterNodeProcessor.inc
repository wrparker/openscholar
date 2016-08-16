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
   * Validates entities.
   * Checks made:
   * * body value is below a set character length
   */
  public function entityValidate($entity) {
    $instances = field_info_instances('node', $entity->type);
    foreach ($instances as $i) {
      $field_name = $i['field_name'];
      $field = field_info_field($field_name);
      if ($field['type'] != 'text_with_summary') {
        continue;
      }

      $value = $entity->{$field_name}[LANGUAGE_NONE][0]['body_value'];
      $max_length = variable_get('os_wysiwyg_maximum_length_' . $field_name, 5 * 1000000);
      if (strlen($value) > $max_length) {
        throw new FeedsValidationException(t('Entity cannot have greater than @max characters.', array('@max' => $max_length)));
      }
    }
  }
}
