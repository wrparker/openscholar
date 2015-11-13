<?php

$plugin = array(
  'label' => t('OS Site Reports'),
  'description' => t('End point for OS Site reports.'),
  'name' => 'report_sites',
  'resource' => 'report_sites',
  'class' => 'OsRestfulSiteReport',
  'data_provider_options' => array(
    'table_name' => 'purl',
    'id_column' => array('id'),
  ),
  'authentication_types' => TRUE,
  'authentication_optional' => FALSE,
);
