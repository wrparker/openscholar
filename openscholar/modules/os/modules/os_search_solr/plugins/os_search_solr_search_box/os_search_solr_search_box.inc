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
    $options = parent::options_defaults();

    $options['num_results'] = 5;
    $options['mlt_type_filters'] = array();
    $options['results_from_type'] = TRUE;
    $options['mlt_fl'] = array('label');

    return $options;
  }

  /**
   * Implements of boxes_content::options_form().
   */
  public function options_form(&$form_state) {
    $form = parent::options_form($form_state);
    $this->options = ($this->options) ? array_merge($this->options_defaults(), $this->options) : $this->options_defaults();

    return $form;
  }

  /**
   * Implements boxes_content::render().
   *
   * @see apachesolr_search_block_view()
   */
  public function render() {
    if (module_exists('vsite') && !vsite_get_vsite()) {
      return array('delta' => $this->delta);
    }

    return drupal_get_form('search_block_form');
  }

}
