<?php

/**
 * Implementation of hook_wysiwyg_plugin().
 */
function os_wysiwyg_liststyle_plugin() {
  $plugins['liststyle'] = array(
    'title' => t('List Style'),
    'vendor url' => 'http://ckeditor.com/addon/liststyle',
    'js file' => 'plugin.js',
//    'js file' => libraries_get_path('ckeditor/plugins/liststyle') . '/plugin.js',
    'settings' => array(),
  );
  return $plugins;
}
