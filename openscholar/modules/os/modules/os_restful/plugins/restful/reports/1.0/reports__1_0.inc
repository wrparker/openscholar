<?php

$plugin = array(
  'label' => t('OS Reports'),
  'description' => t('End point for OS report.'),
  'resource' => 'reports',
  'class' => 'OsRestfulReport',
  'authentication_types' => TRUE,
  'authentication_optional' => FALSE,
);
