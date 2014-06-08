<?php

/**
 * todo:
 * validation: Start date/ End date (validate date format; start date <= end date)
 * Signup (true, false, on, off)
 */

$plugin = array(
  'label' => t('Event'),
  'description' => t('Validating the event content type.'),
  'bundle' => 'event',
  'entity_type' => 'node',
  'class' => 'OsImporterEventValidator',
);
