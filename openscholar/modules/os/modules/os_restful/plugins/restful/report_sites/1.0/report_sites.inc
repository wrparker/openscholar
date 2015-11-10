<?php

$plugin = array(
  'label' => t('OS Site Reports'),
  'description' => t('End point for OS Site reports.'),
  'name' => 'report_sites',
  'resource' => 'report_sites',
  'class' => 'OsRestfulSiteReport',
  'data_provider_options' => array(
    'table_name' => 'node',
    'id_column' => array('nid'),
  ),
  'authentication_types' => TRUE,
  'authentication_optional' => FALSE,
);
