<?php

$plugin = array(
  'label' => t('Activities'),
  'resource' => 'activities',
  'name' => 'profiles__1_0',
  'entity_type' => 'message',
  'bundles' => array(

  ),
  'description' => t('Expose the message entity as activities.'),
  'class' => 'OsActivitiesResource',
);
