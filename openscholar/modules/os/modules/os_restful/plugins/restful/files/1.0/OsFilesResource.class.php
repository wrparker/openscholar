<?php

class OsFilesResource extends RestfulEntityBase {

  /**
   * Overrides RestfulEntityBase::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $info = parent::publicFieldsInfo();

    $info['size'] = array(
      'property' => 'size',
    );

    $info['mimetype'] = array(
      'property' => 'mime',
    );

    $info['url'] = array(
      'property' => 'url',
    );

    $info['type'] = array(
      'property' => 'type',
    );

    $info['name'] = array(
      'property' => 'name',
    );

    $info['timestamp'] = array(
      'property' => 'size',
    );

    $info['description'] = array(
      'property' => 'os_file_description',
      'sub_property' => 'value',
    );

    $info['image_alt'] = array(
      'callback' => array($this, 'getImageAltText'),
    );

    $info['image_title'] = array(
      'callback' => array($this, 'getImageTitleText'),
    );

    $info['preview'] = array(
      'callback' => array($this, 'getFilePreview'),
    );

    $info['terms'] = array(
      'property' => OG_VOCAB_FIELD,
      'callback' => array($this, 'processOgVocabFieldEmpty'),
    );

    return $info;
  }

  /**
   * Helper function for rendering a field.
   */
  private function getBundleProperty($wrapper, $field) {
    $properties = $wrapper->getPropertyInfo();

    if (isset($properties[$field])) {
      $property = $wrapper->get($field);
      return $property->value();
    }

    return null;
  }

  /**
   * Callback function for the alt text of the image.
   */
  public function getImageAltText($wrapper) {
    return $this->getBundleProperty($wrapper, 'field_file_image_alt_text');
  }

  /**
   * Callback function for the title text.
   */
  public function getImageTitleText($wrapper) {
    return $this->getBundleProperty($wrapper, 'field_file_image_title_text');
  }

  /**
   * Callback function for the file preview.
   */
  public function getFilePreview($wrapper) {
    $output = file_view($wrapper->value(), 'preview');
    return drupal_render($output);
  }

  protected function processOgVocabFieldEmpty(\EntityDrupalWrapper $wrapper) {
    $terms = $wrapper->{OG_VOCAB_FIELD}->value();
    $return = array();

    if (empty($terms)) {
      if (!$vsite = $wrapper->{OG_AUDIENCE_FIELD}->get(0)->getIdentifier()) {
        return;
      }

      module_load_include('inc', 'vsite_vocab', 'includes/taxonomy');
      $vocabularies = vsite_vocab_get_vocabularies(vsite_get_vsite($vsite));

      foreach ($vocabularies as $vocabulary) {
        if (og_vocab_load_og_vocab($vocabulary->vid, 'file', $wrapper->getBundle())) {
          $return[] = array(
            'vid' => $vocabulary->vid,
            'label' => $vocabulary->name,
          );
        }
      }

      return $return;
    }

    foreach ($terms as $term) {
      $return[] = array(
        'id' => $term->tid,
        'label' => $term->name,
        'vid' => $term->vid,
      );
    }

    return $return;
  }
}
