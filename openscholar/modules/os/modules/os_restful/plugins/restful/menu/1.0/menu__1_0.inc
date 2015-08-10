<?php

$plugin = array(
  'label' => t('Menu'),
  'description' => t('End point for menus.'),
  'resource' => 'menu',
  'class' => 'OsRestfulMenu',
  'data_provider_options' => array(
    'table_name' => 'menu_links',
    'id_column' => 'mlid',
  ),
  'authentication_types' => TRUE,
  'authentication_optional' => TRUE,
  'hook_menu' => TRUE,
);
