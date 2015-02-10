<?php

class OsFilesResource extends RestfulEntityBase {

  /**
   * Overrides RestfulEntityBase::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $info = parent::publicFieldsInfo();

    $info += array(
      'size' => array(
        'property' => 'size',
      ),
      'mimetype' => array(
        'property' => 'mime',
      ),
      'url' => array(
        'property' => 'url',
      ),
      'type' => array(
        'property' => 'type',
      ),
      'name' => array(
        'property' => 'name',
      ),
      'timestamp' => array(
        'property' => 'timestamp',
      ),
      'description' => array(
        'property' => 'os_file_description',
        'sub_property' => 'value',
      ),
      'image_alt' => array(
        'callback' => array($this, 'getImageAltText'),
      ),
      'image_title' => array(
        'callback' => array($this, 'getImageTitleText'),
      ),
      'preview' => array(
        'callback' => array($this, 'getFilePreview'),
      )
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
}
