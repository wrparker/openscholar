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
      'filename' => array(
        'callback' => array($this, 'getFilename')
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
   * Callback function to get the name of the file on disk
   * We need this to inform the user of what the new filename will be.
   */
  public function getFilename($wrapper) {
    $uri = $wrapper->value()->uri;
    return basename($uri);
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
    if (isset($this->request['vsite'])) {
      $path = db_select('purl', 'p')->fields('p', array('value'))->condition('id', $this->request['vsite'])->execute()->fetchField();
      $destination .= $path.'/files';
    }
    if ($entity = file_save_upload('upload', array(), $destination, FILE_EXISTS_REPLACE)) {

      if (isset($this->request['vsite'])) {
        og_group('node', $this->request['vsite'], array('entity_type' => 'file', 'entity' => $entity));
      }

      if ($entity->status != FILE_STATUS_PERMANENT) {
        $entity->status = FILE_STATUS_PERMANENT;
        $entity = file_save($entity);
      }

      $wrapper = entity_metadata_wrapper($this->entityType, $entity);

      return array($this->viewEntity($wrapper->getIdentifier()));
    }
    elseif (isset($_FILES['files']) && $_FILES['files']['errors']['upload']) {
      throw new RestfulUnprocessableEntityException('Error uploading new file to server.');
    }
    elseif (isset($this->request['embed']) && module_exists('media_internet')) {

      try {
        $provider = media_internet_get_provider($this->request['embed']);
        $provider->validate();
      } catch (MediaInternetNoHandlerException $e) {
        $errors[] = $e->getMessage();
        return;
      } catch (MediaInternetValidationException $e) {
        $errors[] = $e->getMessage();
        return;
      }

      $validators = array();  // TODO: How do we populate this?
      $file = $provider->getFileObject();
      if ($validators) {
        try {
          $file = $provider->getFileObject();
        } catch (Exception $e) {
          form_set_error('embed_code', $e->getMessage());
          return;
        }

        // Check for errors. @see media_add_upload_validate calls file_save_upload().
        // this code is ripped from file_save_upload because we just want the validation part.
        // Call the validation functions specified by this function's caller.
        $errors = array_merge($errors, file_validate($file, $validators));

        if (!empty($errors)) {
          $message = t('%url could not be added.', array('%url' => $embed_code));
          if (count($errors) > 1) {
            $message .= theme('item_list', array('items' => $errors));
          }
          else {
            $message .= ' ' . array_pop($errors);
          }
        }
      }

      if (!empty($errors)) {
        // set error code
        // return errors
      }
      else {
        // Providers decide if they need to save locally or somewhere else.
        // This method returns a file object
        $entity = $provider->save();

        if ($this->request['vsite']) {
          og_group('node', $this->request['vsite'], array('entity_type' => 'file', 'entity' => $entity));
        }

        if ($entity->status != FILE_STATUS_PERMANENT) {
          $entity->status = FILE_STATUS_PERMANENT;
          $entity = file_save($entity);
        }

        $wrapper = entity_metadata_wrapper($this->entityType, $entity);

        return array($this->viewEntity($wrapper->getIdentifier()));
      }
    }
  }

}
