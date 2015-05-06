<?php

class OsFilesResource extends RestfulEntityBase {

  /**
   * Overrides RestfulEntityBase::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $info = parent::publicFieldsInfo();

    $info['size'] = array(
      'property' => 'size',
      'discovery' => array(
        'data' => array(
          'type' => 'int',
          'read_only' => TRUE,
        )
      )
    );

    $info['mimetype'] = array(
      'property' => 'mime',
      'discovery' => array(
        'data' => array(
          'type' => 'string',
          'read_only' => TRUE,
        )
      )
    );

    $info['url'] = array(
      'property' => 'url',
    );

    $info['schema'] = array(
      'callback' => array($this, 'getSchema'),
    );

    $info['filename'] = array(
      'callback' => array($this, 'getFilename'),
      'saveCallback' => array($this, 'updateFileLocation')
    );

    $info['type'] = array(
      'property' => 'type',
      'discovery' => array(
        'data' => array(
          'type' => 'string',
          'read_only' => TRUE,
        )
      )
    );

    $info['name'] = array(
      'property' => 'name',
    );

    $info['timestamp'] = array(
      'property' => 'timestamp',
    );

    $info['description'] = array(
      'property' => 'os_file_description',
      'sub_property' => 'value',
      'saveCallback' => array($this, 'setDescription')
    );

    $info['image_alt'] = array(
      'property' => 'field_file_image_alt_text',
      'sub_property' => 'value',
      'callback' => array($this, 'getImageAltText'),
      'saveCallback' => array($this, 'setImageAltText'),
    );

    $info['image_title'] = array(
      'property' => 'field_file_image_title_text',
      'sub_property' => 'value',
      'callback' => array($this, 'getImageTitleText'),
      'saveCallback' => array($this, 'setImageTitleText'),
    );

    $info['preview'] = array(
      'callback' => array($this, 'getFilePreview'),
      'discovery' => array(
        'data' => array(
          'type' => 'string',
          'read_only' => TRUE,
        )
      )
    );

    $info['terms'] = array(
      'property' => OG_VOCAB_FIELD,
      'process_callbacks' => array(
        array($this, 'processTermsField'),
      ),
    );

    unset($info['label']['property']);

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
   * Callback function to get the schema of the file.
   * We use this to prevent user from changing the filename
   */
  public function getSchema($wrapper) {
    $uri = $wrapper->value()->uri;
    return parse_url($uri, PHP_URL_SCHEME);
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
   * Filter files by vsite
   */
  protected function queryForListFilter(EntityFieldQuery $query) {
    if ($this->request['vsite']) {
      if ($vsite = vsite_get_vsite($this->request['vsite'])) {
        $query->fieldCondition(OG_AUDIENCE_FIELD, 'target_id', $this->request['vsite']);
      }
      else {
        throw new RestfulBadRequestException(t('No vsite with the id @id', array('@id' => $this->request['vsite'])));
      }
    }
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
      $destination .= $path . '/files';
    }
    if ($entity = file_save_upload('upload', array(), $destination, FILE_EXISTS_REPLACE)) {

      if (isset($this->request['vsite'])) {
        og_group('node', $this->request['vsite'], array('entity_type' => 'file', 'entity' => $entity));
        $entity = file_load($entity->fid);
      }

      if ($entity->status != FILE_STATUS_PERMANENT) {
        $entity->status = FILE_STATUS_PERMANENT;
        $entity = file_save($entity);
      }

      $wrapper = entity_metadata_wrapper($this->entityType, $entity);

      return array($this->viewEntity($wrapper->getIdentifier()));
    } elseif (isset($_FILES['files']) && $_FILES['files']['errors']['upload']) {
      throw new RestfulUnprocessableEntityException('Error uploading new file to server.');
    } elseif (isset($this->request['embed']) && module_exists('media_internet')) {

      try {
        $provider = media_internet_get_provider($this->request['embed']);
        $provider->validate();
      }
      catch (MediaInternetNoHandlerException $e) {
        $errors[] = $e->getMessage();
        return;
      }
      catch (MediaInternetValidationException $e) {
        $errors[] = $e->getMessage();
        return;
      }

      $validators = array();  // TODO: How do we populate this?
      $file = $provider->getFileObject();
      if ($validators) {
        try {
          $file = $provider->getFileObject();
        }
        catch (Exception $e) {
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
          } else {
            $message .= ' ' . array_pop($errors);
          }
        }
      }

      if (!empty($errors)) {
        // set error code
        // return errors
      } else {
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

  public function processTermsField($terms) {
    $return = array();

    foreach ($terms as $term) {
      $return[] = array(
        'id' => $term->tid,
        'label' => $term->name,
        'vid' => $term->vid,
      );
    }

    return $return;
  }

  /**
   * Override. We need to handle files being replaced through this method.
   */
  public function putEntity($entity_id) {

    // this request is only a file
    // no other data is addeed
    if ($this->request['file']) {
      $oldFile = file_load($entity_id);
      $this->request['file']->filename = $oldFile->filename;
      if ($file = file_move($this->request['file'], $oldFile->uri, FILE_EXISTS_REPLACE)) {

        return array($this->viewEntity($entity_id));
      }
      else {
        throw new RestfulBadRequestException('Error moving file.');
      }
    }

    return parent::putEntity($entity_id);
  }

  protected function setPropertyValues(EntityMetadataWrapper $wrapper, $null_missing_fields = FALSE) {
    $request = $this->getRequest();

    static::cleanRequest($request);
    $save = FALSE;
    $original_request = $request;

    foreach ($this->getPublicFields() as $public_field_name => $info) {
      if (empty($info['property']) && empty($info['saveCallback'])) {
        // We may have for example an entity with no label property, but with a
        // label callback. In that case the $info['property'] won't exist, so
        // we skip this field.
        continue;
      }

      if (isset($info['saveCallback'])) {
        $save = $save || call_user_func($info['saveCallback'], $wrapper);

        if ($save) {
          unset($original_request[$public_field_name]);
        }
      }
      elseif ($info['property']) {
        $property_name = $info['property'];

        if (!isset($request[$public_field_name])) {
          // No property to set in the request.
          if ($null_missing_fields && $this->checkPropertyAccess('edit', $public_field_name, $wrapper->{$property_name}, $wrapper)) {
            // We need to set the value to NULL.
            $wrapper->{$property_name}->set(NULL);
          }
          continue;
        }

        if (!$this->checkPropertyAccess('edit', $public_field_name, $wrapper->{$property_name}, $wrapper)) {
          throw new RestfulBadRequestException(format_string('Property @name cannot be set.', array('@name' => $public_field_name)));
        }

        $field_value = $this->propertyValuesPreprocess($property_name, $request[$public_field_name], $public_field_name);

        $wrapper->{$property_name}->set($field_value);
        unset($original_request[$public_field_name]);
        $save = TRUE;
      }
    }


    if (!$save) {
      // No request was sent.
      throw new RestfulBadRequestException('No values were sent with the request');
    }

    if ($original_request) {
      // Request had illegal values.
      $error_message = format_plural(count($original_request), 'Property @names is invalid.', 'Property @names are invalid.', array('@names' => implode(', ', array_keys($original_request))));
      throw new RestfulBadRequestException($error_message);
    }

    // Allow changing the entity just before it's saved. For example, setting
    // the author of the node entity.
    $this->entityPreSave($wrapper);

    $this->entityValidate($wrapper);

    $wrapper->save();
  }

  protected function updateFileLocation($wrapper) {
    if ($this->request['filename']) {
      $file = file_load($wrapper->getIdentifier());
      $label = $wrapper->name->value();
      $destination = dirname($file->uri) . '/' . $this->request['filename'];
      if ($file = file_move($file, $destination)) {
        $wrapper->set($file);
        $wrapper->name->set($label);
        return true;
      }
    }
    return false;
  }

  protected function setDescription($wrapper) {
    if ($this->request['description']) {
      $data = array(
        'value' => $this->request['description'],
        'format' => 'filtered_html'
      );
      $wrapper->os_file_description->set($data);

      return true;
    }
    return false;
  }

  protected function setImageAltText($wrapper) {
    if ($this->request['image_alt']) {
      $wrapper->field_file_image_alt_text->set($this->request['image_alt']);

      return true;
    }
    return false;
  }

  protected function setImageTitleText($wrapper) {
    if ($this->request['image_title']) {
      $wrapper->field_file_image_title_text->set($this->request['image_title']);

      return true;
    }
    return false;
  }
}
