<?php

/**
 * Implements hook_cp_settings().
 */
function grouper_cp_settings() {
  $forms = array();

  $forms['grouper'] = array(
    'form' => array(
      '#access' => user_access('manage grouper groups'),
      '#custom_directive' => 'grouper',
      '#custom_directive_parameters' => array(
        'ng-if' => "\$parent.formData.vsite_private == 'grouper'"
      ),
      '#default_value' => grouper_site_groups(),
      '#weight' => 2
    ),
    'group' => array(
      '#id' => 'privacy',
      '#title' => t('Site Visibility'),
      '#weight' => 10,
      '#help_link' => l(t('Learn more about site visibility'), 'http://docs.openscholar.harvard.edu/global-site-settings#site-visibility', array('attributes' => array('target' => '_blank')))
    )
  );

  return $forms;
}
