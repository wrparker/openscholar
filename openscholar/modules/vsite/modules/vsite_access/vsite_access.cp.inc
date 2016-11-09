<?php

/**
 * Implements hook_cp_settings().
 *
 * Add the vsite privacy/publicity form element to CP settings.
 */
function vsite_access_cp_settings() {
  $form = array();
  $space = spaces_get_space();

  if (!empty($space)) {
    // Gets the vsite_users_contact_form_anonymous variable.
    $wrapper = entity_metadata_wrapper('node', $space->group);

    $form['vsite_private'] = array(
      'group' => array(
        '#id' => 'privacy',
        '#title' => t('Site Visibility'),
        '#weight' => 10,
        '#help_link' => l(t('Learn more about site visibility'), 'http://docs.openscholar.harvard.edu/site-settings#privacy', array('attributes' => array('target' => '_blank')))
      ),
      'form' => array(
        '#type' => 'radios',
        '#title' => t('Choose who can view your site. Visibility changes will take effect in 2 minutes.'),
        '#default_value' => $wrapper->{VSITE_ACCESS_FIELD}->value(),
        '#options' => vsite_access_privacy_available_values(),
        '#attached' => array(
          'js' => array(
            drupal_get_path('module', 'vsite_access') . '/vsite_access.js'
          )
        ),
      ),
      'submit' => array(
        'vsite_access_cp_settings_node_submit',
      ),
      'rest_submit' => 'vsite_access_cp_settings_save'
    );
  }

  return $form;
}

/**
 * Submit callback for cp/settings form.
 *
 * Sets vsite_privacy value for the node.
 *
 * @see vsite_cp_settings()
 */
function vsite_access_cp_settings_node_submit($form, &$form_state) {
  // Get the current vsite.
  $space = spaces_get_space();
  // Get the vsite_privacy value.
  if (isset($form_state['values']['vsite_private']) && $space) {
    vsite_access_cp_settings_save($form_state['values']['vsite_private']);
  }
}

/**
 * Save the vsite privacy level.
 */
function vsite_access_cp_settings_save($val) {
  $space = spaces_get_space();
  $node = $space->group;

  $current_vsite_access = $node->{VSITE_ACCESS_FIELD}[LANGUAGE_NONE][0]['value'];
  $node->{VSITE_ACCESS_FIELD}[LANGUAGE_NONE][0]['value'] = $val;
  
  node_save($node);

  // Clear the cache when we switch the privacy in ALL cases except toggling between invite only (value = 1) and Harvard (value = 4).
  // For toggling, the sum of previous and selected value will be always '5' for such case; other two radio buttons values are 0 and 2.
  if (($current_vsite_access + $val) != 5 && $current_vsite_access != $val) {
    cp_clear_site_cache();
  }
}
