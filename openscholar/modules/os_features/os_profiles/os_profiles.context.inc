<?php
/**
 * @file
 * os_profiles.context.inc
 */

/**
 * Implements hook_context_default_contexts().
 */
function os_profiles_context_default_contexts() {
  $export = array();

  $context = new stdClass();
  $context->disabled = FALSE; /* Edit this to true to make a default context disabled initially */
  $context->api_version = 3;
  $context->name = 'profiles_profiles';
  $context->description = '';
  $context->tag = 'OS features';
  $context->conditions = array(
    'menu' => array(
      'values' => array(
        'people' => 'people',
      ),
    ),
    'node' => array(
      'values' => array(
        'person' => 'person',
      ),
      'options' => array(
        'node_form' => '0',
      ),
    ),
    'path' => array(
      'values' => array(
        'people' => 'people',
        'people/*' => 'people/*',
        'browse/people' => 'browse/people',
      ),
    ),
    'views' => array(
      'values' => array(
        'os_profiles' => 'os_profiles',
        'os_profiles:page' => 'os_profiles:page',
      ),
    ),
  );
  $context->reactions = array();

  if (variable_get('os_profiles_display_filter_by_alphabetical_group', 0)) {
    $context->reactions['block']['blocks']['views-os_profiles-profile_alphabet_group_block'] = array(
      'module' => 'views',
      'delta' => 'os_profiles-profile_alphabet_group_block',
      'region' => 'sidebar_second',
      'weight' => -8,
    );
  }
  
  $context->condition_mode = 0;

  // Translatables
  // Included for use with string extractors like potx.
  t('OS features');
  $export['profiles_profiles'] = $context;

  return $export;
}
