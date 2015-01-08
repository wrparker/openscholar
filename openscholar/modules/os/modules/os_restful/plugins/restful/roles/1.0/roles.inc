<?php

$plugin = array(
  'label' => t('Roles'),
  'description' => t('Export the "Roles" table.'),
  'resource' => 'roles',
  'class' => 'OsRestfulRoles',
  'data_provider_options' => array(
    'table_name' => 'role',
    'id_column' => 'rid',
  ),
  'authentication_types' => TRUE,
  'authentication_optional' => TRUE,
);
