<?php

$plugin = array(
  'label' => t('Blog'),
  'description' => t('Validating the blog content type.'),
  'bundle' => 'blog',
  'entity_type' => 'node',
  'class' => 'OsImporterBlogValidator',
);
