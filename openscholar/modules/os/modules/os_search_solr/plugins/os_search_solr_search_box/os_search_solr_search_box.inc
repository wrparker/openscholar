<?php
/**
 * @file os_search_solr_search_box.inc
 *
 * Improved search box.
 */
class os_search_solr_search_box extends os_boxes_default {

  /**
   * Create a new box.
   */
  protected function __construct() {
    parent::__construct();
  }

  /**
   * Implements of boxes_content::options_defaults().
   */
  public function options_defaults() {
    return parent::options_defaults() + array(
      'bundle' => array(),
      'terms' => array(),
      'vocab' => array(),
      'sort' => '',
    );
  }

  /**
   * Implements of boxes_content::options_form().
   */
  public function options_form(&$form_state) {
    $form = parent::options_form($form_state);
    $this->options = ($this->options) ? array_merge($this->options_defaults(), $this->options) : $this->options_defaults();

    $bundle = $this->options['bundle'];
    $bundles = os_get_bundles(array(OS_PUBLIC_APP, OS_PRIVATE_APP));
    // Use first bundle of the list in case none was selected before.
    if (!$bundle) {
      $bundles_keys = array_keys($bundles);
      $bundle = reset($bundles_keys);
    }

    $form['bundle'] = array(
      '#type' => 'select',
      '#description' => t('Which bundle would you like to use.'),
      '#title' => t('Content type'),
      '#options' => $bundles,
      '#required' => TRUE,
      '#default_value' => $bundle,
      '#multiple' => TRUE,
    );

    ctools_include('taxonomy', 'os');

    $form['vocab'] = array(
      '#type' => 'select',
      '#title' => t('Filter by: vocab'),
      '#default_value' => $this->options['vocab'],
      '#options' => array(
        '-none-' => t('None'),
      ),
      '#ajax' => array(
        'callback' => 'os_search_solr_vocabularies_switch',
        'wrapper' => 'terms',
      ),
    );

    $vocabularies = taxonomy_vocabulary_load_multiple(array_keys(os_get_vocabularies()));

    foreach ($vocabularies as $vocabulary) {
      $form['vocab']['#options'][$vocabulary->vid] = $vocabulary->name;
    }

    $form['terms'] = array(
      '#type' => 'markup',
      '#tree' => TRUE,
      '#prefix' => '<div id="terms">',
      '#suffix' => '</div>',
    );

    if ($this->options['vocab'] != 'none' || !empty($form_state['values']['vocab'])) {
      if (!empty($form_state['values']['vocab'])) {
        $vocab = $form_state['values']['vocab'];
      }
      else {
        $vocab = $this->options['vocab'];
      }

      $terms = taxonomy_get_tree($vocab, 0, NULL, 1);
      $taxonomies = array();
      foreach ($terms as $term) {
        $taxonomies[$term->tid] = $term->name;
      }
      $form['terms']['terms'] = array(
        '#type' => 'checkboxes',
        '#title' => t('Filter by: terms'),
        '#options' => $taxonomies,
        '#default_value' => $this->options['terms']['terms'],
      );
    }

    $form['sort'] = array(
      '#type' => 'select',
      '#title' => t('Sort by'),
      '#options' => array(
        'asc' => t('Ascending'),
        'desc' => t('Descending'),
      ),
      '#default_value' => $this->options['sort'],
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $return = parent::render();

    if (module_exists('vsite') && !vsite_get_vsite()) {
      return $return;
    }

    $form_state['box_settings'] = $this->options;
    $form = drupal_build_form('search_block_form', $form_state);
    $return['content'] = render($form);

    return $return;
  }

}
