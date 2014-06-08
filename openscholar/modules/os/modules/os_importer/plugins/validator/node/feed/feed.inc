<?php

$plugin = array(
  'label' => t('Feed'),
  'description' => t('Validating the feed content type.'),
  'bundle' => 'feed',
  'entity_type' => 'node',
  'class' => 'OsImporterFeedValidator',
);
