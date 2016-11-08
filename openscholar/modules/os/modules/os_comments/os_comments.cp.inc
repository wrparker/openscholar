<?php

/**
 * Implements hook_cp_settings().
 *
 * Adds field "Disqus shortname" to the CP > Settings form.
 */
function os_comments_cp_settings() {
  $settings = array();

  // Returns empty array if Disqus module is not enabled.
  if (!module_exists('disqus')) {
    return $settings;
  }

  $vsite = vsite_get_vsite();
  if ($vsite) {
    $disqus_shortname = $vsite->controllers->variable->get('disqus_domain', 'space');
  }
  else {
    $disqus_shortname = variable_get('disqus_domain', '');
  }

  $settings['disqus_domain'] = array(
    'group' => array(
      '#id' => 'social',
      '#title' => t('Social'),
      '#weight' => 0,
    ),
    'form' => array(
      '#type' => 'textfield',
      '#title' => t('Disqus Shortname'),
      '#default_value' => $disqus_shortname,
      '#description' => t('The website shortname that you registered Disqus with. If you registered http://example.disqus.com, you would enter "example" here. It is called "shortname" on the disqus site. You can <a href="http://disqus.com/" title="Disqus Signup">signup here</a> for a disqus account if you do not have one.'),
    ),
  );

  return $settings;
}