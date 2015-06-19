<?php
/**
 * @file
 *
 * Functions for defining and implementing an angular drag and drop field for files
 */

function media_browser_field_widget_info() {
  return array(
    'media_browser_widget' => array(
      'label' => t('Angular Media Browser'),
      'field types' => array('file'),
      'settings' => array(
        'upload_button_text' => t('Upload'),
        'droppable_area_text' => t('Drag your files here'),
        // This settings allows to drop multiple files at once.
        'allowed_types' => array('document', 'image', 'audio', 'video', 'executable', 'html'),
        'browser_plugins' => array(),
        'allowed_schemes' => array('public', 'private'),
      ),
      'behaviors' => array(
        'multiple values' => FIELD_BEHAVIOR_CUSTOM,
        'default value' => FIELD_BEHAVIOR_NONE,
      ),
    )
  );
}

function media_browser_file_load($files) {
  foreach ($files as $file) {
    $file->icon = file_icon_url($file);
  }
}

/**
 * Implements hook_field_widget_settings_form.
 *
 * Adds fields to the media widget settings form
 */
function media_browser_field_widget_settings_form($field, $instance) {
  $settings = $instance['widget']['settings'];

  $form['upload_button_text'] = array(
    '#title' => t('Upload Button Text'),
    '#type' => 'textfield',
    '#default_value' => $settings['upload_button_text'],
  );

  $form['droppable_area_text'] = array(
    '#title' => t('Text in Droppable Area'),
    '#type' => 'textfield',
    '#default_value' => $settings['droppable_area_text'],
  );

  $form += media_field_widget_settings_form($field, $instance);

  return $form;
}

/**
 * Implements hook_field_widget_form.
 *
 * Doesn't have to do much, since nearly all of the logic and rendering is done by angularjs
 */
function media_browser_field_widget_form(&$form, &$form_state, $field, $instance, $langcode, $items, $delta, $element) {
  $field_settings = $instance['settings'];
  $widget_settings = $instance['widget']['settings'];

  $element += array(
    '#field' => $field,
    '#instance' => $instance,
    '#attached' => array(
      'library' => array(
        array('os_common', 'FileEditorModal'),
        array('media', 'media_browser')
      ),
      'js' => array(
        drupal_get_path('module', 'media_browser').'/misc/MediaBrowserField.js',
        array(
          'type' => 'setting',
          'data' => array(
            'mediaBrowserField' => array(
              drupal_html_class('edit-'.$element['#field_name']) => array(
                'selectedFiles' => array(
                )
              ),
            )
          )
        )
      ),
    ),
    '#markup' => format_string('<div media-browser-field max-filesize="@filesize" types="@types" extensions="@extensions" upload-text="@upload-text" droppable-text="@droppable-text"></div>', array(
      '@filesize' => isset($field_settings['max_filesize']) ? $field_settings['max_filesize'] : 0,
      '@types' => implode(',', array_filter($widget_settings['allowed_types'])),
      '@extensions' => isset($field_settings['file_extensions']) ? $field_settings['file_extensions'] : media_variable_get('file_extensions'),
      '@upload-text' => $widget_settings['upload_button_text'],
      '@droppable-text' => $widget_settings['droppable_area_text']
    ))
  );

  dpm($element);

  return $element;
}
