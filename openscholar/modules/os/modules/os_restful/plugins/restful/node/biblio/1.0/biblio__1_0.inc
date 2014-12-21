<?php

$plugin = array(
  'label' => t('Biblio'),
  'resource' => 'biblio',
  'name' => 'biblio__1_0',
  'entity_type' => 'node',
  'bundle' => 'biblio',
  'description' => t('Expose biblio bundle via restufl'),
  'class' => 'BlogNodeRestfulBase',
  'authentication_types' => TRUE,
  'authentication_optional' => TRUE,
);
