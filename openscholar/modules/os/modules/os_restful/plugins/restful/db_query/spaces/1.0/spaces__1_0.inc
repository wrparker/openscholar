<?php

$plugin = array(
  'label' => t('Spaces'),
  'description' => t('Export the spaces table.'),
  'resource' => 'Spaces',
  'class' => 'OsRestfulSpaces',
  'data_provider_options' => array(
    'table_name' => 'spaces_overrides',
    'id_column' => 'sid',
  ),
  'menu_item' => 'space',
  'authentication_types' => TRUE,
  'authentication_optional' => TRUE,
);
