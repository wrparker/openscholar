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
   * {@inheritdoc}
   */
  public function render() {
    $return = parent::render();

    if (module_exists('vsite') && !vsite_get_vsite()) {
      return $return;
    }

    $form = drupal_get_form('search_block_form');
    $return['content'] = render($form);

    return $return;
  }

}
