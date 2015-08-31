<?php
/**
 * @file
 * cp.form.inc
 */

/**
 * Form builder; merges various site settings into a single "Settings" form.
 */
function cp_settings_form($form_state) {
  $form = array();
  $form['#submit'] = array();
  $form['#after_build'] = array();

  // Loops over all implementations of hook_cp_settings().
  $settings = cp_get_setting_forms();
  foreach ($settings as $key => $setting) {
    if (!isset($setting['form'])) {
      continue;
    }

    // Creates any non-existing fieldgroup for this setting, if specified.
    if (isset($setting['group'])) {
      $id = $setting['group']['#id'];
      if (!isset($form[$id])) {
        $form[$id] = $setting['group'];
        $form[$id]['#type'] = 'fieldset';
      }

      $form[$id][$key] = $setting['form'];
    }
    else {
      $form[$key] = $setting['form'];
    }

    // Merges any submit handler(s) into the existing value.
    if (isset($setting['submit'])) {
      $form['#submit'] = array_merge($form['#submit'], $setting['submit']);
    }
    if (isset($setting['after_build'])) {
      $form['#after_build'] = array_merge($form['#after_build'], $setting['after_build']);
    }
  }

  return system_settings_form($form);
}

/**
 * Spaces features form.
 */
function cp_app_settings_form($form, &$form_state) {
  $spaces_features = variable_get('spaces_features', array());
  $space = spaces_get_space();
  $features = spaces_features($space ? $space->type : 'site');

  $sort_array = array();
  foreach ($features as $feature => $info) {
    $sort_array[$feature] = $info->info['name'];
  }
  asort($sort_array);
  $i = 0;
  foreach ($sort_array as $feature => $name) {
    $form['spaces_features'][$feature]['#weight'] = $i++;
  }

  foreach ($features as $feature => $info) {
    $label = "<strong>" . t($info->info['name']) . "</strong>";
    $label .= "<div class='description'>" . t($info->info['description']) . "</div>";
    $form['labels'][$feature] = array(
      '#type' => 'markup',
      '#markup' => $label,
    );
    $form['spaces_features'][$feature] = array(
      '#type' => 'select',
      '#options' => $space ? $space->feature_options() : array(
          0 => t('Disabled'),
          1 => t('Enabled'),
        ),
      '#default_value' => isset($spaces_features[$feature]) ? $spaces_features[$feature] : 0,
    );
  }

  $form = system_settings_form($form);
  $form['#theme'] = 'spaces_features_form';
  $form['actions']['#weight'] = 100;
  return $form;
}

/**
 * Validation for site_frontpage setting in spaces forms.
 */
function spaces_site_frontpage_validate($element, &$form_state) {
  if ($element['#value'] == 'spaces-other') {
    $path = $form_state['values']['site_frontpage_path'];
    $form_state['values']['site_frontpage'] = $element['#value'] = $path;
    unset($form_state['values']['site_frontpage_path']);
  }
  if ($element['#value'] !== 'spaces-frontpage') {
    $item = menu_get_item($element['#value']);
    if (!$item) {
      form_error($element, t('Invalid front page selected'));
    }
  }
}

function cp_menu_form_alter(&$form, &$form_state, $form_id) {
  if ($space = spaces_get_space()) {

    // Add our submit handler to system settings forms
    if (!empty($form['#submit'])) {
      $position = array_search('system_settings_form_submit', $form['#submit'], TRUE);
      if ($position !== FALSE) {
        $form['#submit'][$position] = 'spaces_system_settings_form_submit';

        // For administrators, let them save to either the space or the preset,
        // unless it looks like the buttons may have already been altered.
        if (isset($form['actions']) && count(element_children($form['actions'])) == 1 &&
          spaces_access_admin_perms(array('administer spaces'))
        ) {
          if ($preset = $space->controllers->variable->get('spaces_preset_' . $space->type)) {
            $preset = spaces_preset_load($preset, $space->type);
            $form['actions']['preset'] = array(
              '#type' => 'submit',
              '#value' => t('Save to @title', array('@title' => $preset->title)),
            );
            $form['actions']['reset'] = array(
              '#type' => 'submit',
              '#value' => t('Reset to defaults'),
            );
            $form['actions']['submit']['#value'] = t('Save for @name', array(
              '@name' => $space->title(),
            ));
          }
        }
      }
    }

    // Add the #space value to all forms generated in a space to enable non-
    // purl space-types to handle AHAH callbacks. @see spaces_ahah_check()
    $form['#space'] = array(
      '#type' => 'value',
      '#value' => $space->type . ':' . $space->id,
    );
  }
}
