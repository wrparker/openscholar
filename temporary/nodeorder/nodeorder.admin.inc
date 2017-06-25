<?php

/**
 * @file
 * Admin page callbacks for the nodeorder module.
 */

/**
 * Returns HTML for the vocabulary overview form as a sortable list of vocabularies.
 *
 * @param $variables
 *   An associative array containing:
 *   - form: A render element representing the form.
 *
 * @see taxonomy_overview_vocabularies()
 * @ingroup themeable
 */
function theme_nodeorder_admin_display_form($variables) {
  $form = $variables['form'];

  drupal_add_tabledrag('nodeorder', 'order', 'sibling', 'node-weight');

  $errors = form_get_errors() != FALSE ? form_get_errors() : array();
  $rows = array();
  foreach (element_children($form) as $key) {
    if (isset($form[$key]['#node'])) {
      $node = &$form[$key];

      $row = array();
      $row[] = drupal_render($node['title']);
      $node['weight']['#attributes']['class'] = array('node-weight');
      $row[] = drupal_render($node['weight']);
      $row = array('data' => $row);
      $rows[$key] = $row;
    }
  }

  // Add necessary classes to rows.
  $row_position = 0;
  foreach ($rows as $key => $row) {
    $rows[$key]['class'] = array();
    $rows[$key]['class'][] = 'draggable';

    // Add an error class if this row contains a form error.
    foreach ($errors as $error_key => $error) {
      if (strpos($error_key, $key) === 0) {
        $rows[$key]['class'][] = 'error';
      }
    }
    $row_position++;
  }

  if (empty($rows)) {
    $rows[] = array(array('data' => $form['#empty_text'], 'colspan' => '3'));
  }

  $header = array(t('Title'), t('Weight'), );
  $output = theme('table', array('header' => $header, 'rows' => $rows, 'attributes' => array('id' => 'nodeorder')));
  $output .= drupal_render_children($form);
  $output .= theme('pager');

  return $output;
}
