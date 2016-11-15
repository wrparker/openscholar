<?php

/**
 * Menu callback; Set the white listed domains for embedded media.
 */
function media_embed_settings($form, &$form_state) {
  $domains = variable_get('media_embed_whitelist', array());
  $form = array();

  $form['whitelist'] = array(
    '#type' => 'textarea',
    '#title' => t('Domain Whitelist'),
    '#default_value' => implode("\n", $domains),
    '#description' => t('Enter the list of domains that users may add iframe, object, embed and script tags from. One domain per line. <b>Do not add domains with http:// or https://</b>'),
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Submit'),
  );

  return $form;
}

/**
 * Validate handler; Verify the domains matching to the correct format.
 */
function media_embed_settings_validate($form, $form_state) {

  $is_https = is_int(strpos($form_state['values']['whitelist'], 'https://'));
  $is_http = is_int(strpos($form_state['values']['whitelist'], 'http://'));

  if ($is_https || $is_http) {
    form_set_error('whitelist', t('The domains cannot contains <b>https://</b> or <b>http://</b>'));
  }
}

/**
 * Submit handler; Set the white listed domains.
 */
function media_embed_settings_submit($form, $form_state) {
  $domains = $form_state['values']['whitelist'];

  $domains = array_merge(array_filter(preg_split("[\n\r|\n|\r]", $domains)));
  variable_set('media_embed_whitelist', $domains);
}
