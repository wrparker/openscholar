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

  /**
   * Override. Handle the file upload process before creating an actual entity.
   * The file could be a straight replacement, and this is where we handle that.
   */
  public function createEntity() {
    if ($this->checkEntityAccess('create', 'file', NULL) === FALSE) {
      // User does not have access to create entity.
      $params = array('@resource' => $this->getPluginKey('label'));
      throw new RestfulForbiddenException(format_string('You do not have access to create a new @resource resource.', $params));
    }

    $destination = 'public://';
    // do spaces/private file stuff here
    if ($this->request['vsite']) {
      $path = db_select('purl', 'p')->fields('p', array('value'))->condition('id', $this->request['vsite'])->execute()->fetchField();
      $destination .= $path.'/files';
    }
    if ($entity = file_save_upload('upload', array(), $destination, FILE_EXISTS_REPLACE)) {

      if ($this->request['vsite']) {
        og_group('node', $this->request['vsite'], array('entity_type' => 'file', 'entity' => $entity));
      }

      $entity->status = FILE_STATUS_PERMANENT;
      $entity = file_save($entity);

      $wrapper = entity_metadata_wrapper($this->entityType, $entity);

      $this->setPropertyValues($wrapper);
      return array($this->viewEntity($wrapper->getIdentifier()));
    }
    elseif ($_FILES['files']['errors']['upload']) {
      throw new RestfulUnprocessableEntityException('Error uploading new file to server.');
    }
    else {
      // probably a url or html snippet
    }
  }

}
