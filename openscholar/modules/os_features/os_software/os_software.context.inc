<?php
/**
 * @file
 * os_software.context.inc
 */

/**
 * Implements hook_context_default_contexts().
 */
function os_software_context_default_contexts() {
  $export = array();

  $context = new stdClass();
  $context->disabled = FALSE; /* Edit this to true to make a default context disabled initially */
  $context->api_version = 3;
  $context->name = 'software_software';
  $context->description = '';
  $context->tag = 'OS features';
  $context->conditions = array(
    'node' => array(
      'values' => array(
        'software_project' => 'software_project',
        'software_release' => 'software_release',
      ),
      'options' => array(
        'node_form' => '0',
      ),
    ),
    'path' => array(
      'values' => array(
        'software' => 'software',
        'software/*' => 'software/*',
        'browse/projects' => 'browse/projects',
        'browse/releases' => 'browse/releases',
      ),
    ),
    'views' => array(
      'values' => array(
        'os_software_projects' => 'os_software_projects',
        'os_software_projects:page' => 'os_software_projects:page',
        'os_software_releases' => 'os_software_releases',
        'os_software_releases:page_1' => 'os_software_releases:page_1',
      ),
    ),
  );
  $context->reactions = array(
    'block' => array(
      'blocks' => array(
        'boxes-os_addthis' => array(
          'module' => 'boxes',
          'delta' => 'os_addthis',
          'region' => 'sidebar_second',
          'weight' => '-9',
        ),
        'boxes-os_software_releases-block_1' => array(
          'module' => 'boxes',
          'delta' => 'os_software_releases-block_1',
          'region' => 'sidebar_second',
          'weight' => '-11',
          'label' => 'Recent Software Releases',
        ),
      ),
    ),
    'menu' => 'software',
  );
  $context->condition_mode = 0;

  // Translatables
  // Included for use with string extractors like potx.
  t('OS features');
  $export['software_software'] = $context;

  return $export;
}
