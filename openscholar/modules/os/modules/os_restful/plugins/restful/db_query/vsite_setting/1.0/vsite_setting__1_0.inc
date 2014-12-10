<?php

$plugin = array(
  'label' => t('vsite Setting'),
  'description' => t('Export the spaces overrides table.'),
  'resource' => 'vsite_setting',
  'class' => 'OsRestfulSpacesOverrides',
  'data_provider_options' => array(
    'table_name' => 'spaces_overrides',
    'id_column' => array('object_type', 'object_id', 'id'),
  ),
  'authentication_types' => TRUE,
  'authentication_optional' => TRUE,
  'hook_menu' => TRUE,
);
