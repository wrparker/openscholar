<?php

/**
 * Implementation of hook_wysiwyg_plugin().
 */
function os_wysiwyg_pastefromword_plugin() {
  $plugins['pastefromword'] = array(
    'title' => t('Paste from Word'),
    'icon path' => libraries_get_path('ckeditor') . '/plugins/pastefromword/icons',
    'icon file' => 'pastefromword.png',
    'icon title' => t('Paste from word'),
    'js path' => libraries_get_path('ckeditor') . '/plugins/pastefromword',
    'js file' => 'plugin.js',
    'settings' => array(),
  );
  return $plugins;
}
