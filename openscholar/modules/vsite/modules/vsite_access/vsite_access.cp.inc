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
