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
      'sort' => array(),
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

    $form['terms'] = array(
      '#type' => 'select',
      '#title' => t('Filter by: vocab'),
      '#default_value' => $this->options['terms'],
    );

    $vocabularies = taxonomy_vocabulary_load_multiple(array_keys(os_get_vocabularies()));

    foreach ($vocabularies as $vocabulary) {
      $form['terms']['#options'][$vocabulary->vid] = $vocabulary->name;
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

    $form = drupal_get_form('search_block_form', array('widget_options' => $this->options));
    $return['content'] = render($form);

    return $return;
  }

}
