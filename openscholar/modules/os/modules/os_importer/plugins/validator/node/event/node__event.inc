<?php

$plugin = array(
  'label' => t('Event'),
  'description' => t('Validating the event content type.'),
  'entity_type' => 'node',
  'bundle' => 'event',
  'class' => 'OsImporterEventValidator',
);
