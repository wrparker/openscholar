<?php

/**
 * @file
 * The class for Dataverse Dataset Citation boxes.
 */

class os_boxes_dataverse_dataset_citation extends os_boxes_default {

  private $dataverse_base_url_default = "https://dataverse-internal.iq.harvard.edu";

  public $embed_as = array(
    'iframe',
  );

  /**
   * Implementation of boxes_content::options_defaults().
   */
  public function options_defaults(){
    $options = parent::options_defaults();
    $options['persistent_id'] = '';
    $options['embed_height'] = '500px';
    $options['visibility'] = 'feature';
    return $options;
  }

  /**
   * Implementation of boxes_content::options_form().
   */
  public function options_form(&$form_state){
    $form = parent::options_form($form_state);

    $base_url = variable_get("dataverse_base_url", $this->dataverse_base_url_default) . "/dataverse/";

    $form['persistent_id'] = array(
      '#type' => 'textfield',
      '#title' => t('Dataset Persistent Identifier'),
      '#description' => t('Need help finding your Dataset Persistent Identifier? Learn how to here: ') . l('http://docs.openscholar.harvard.edu/dataverse'),
      '#required' => TRUE,
      '#field_prefix' => $base_url,
      '#default_value' => $this->options['persistent_id'],
    );

    $form['embed_height'] = array(
      '#type' => 'textfield',
      '#title' => 'Embed Height',
      '#default_value' => $this->options['embed_height'],
    );

    return $form;
  }

  /**
   * Implementation of boxes_content::render().
   */
  public function render() {
    if (module_exists('vsite') && !vsite_get_vsite()) {
      return;
    }

    $embed_height = preg_replace('/\D/', '', $this->options['embed_height']);
    $base_url = variable_get("dataverse_base_url", $this->dataverse_base_url_default) . "/resources/js/widgets.js?persistentId=" . $this->options['persistent_id'] . "&amp;dvUrl=" . variable_get("dataverse_base_url", $this->dataverse_base_url_default) . "&amp;widget=citation&amp;heightPx=" . $embed_height;

    $block = parent::render();
    $block['content'] = "<script src=\"$base_url\"></script>";
    return $block;
  }

}
