<?php

class OsFilesResource extends RestfulEntityBase {

  /**
   * @api {get} api/files/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Files
   *
   * @apiDescription Consume files.
   *
   * @apiParam {Number} id The file ID
   *
   * @apiSuccess {Number}   id        The file ID.
   * @apiSuccess {String}   label     File mame.
   * @apiSuccess {Integer}  size      Size in bytes.
   * @apiSuccess {String}   mimetype  File mimetype.
   * @apiSuccess {String}   url       The direct URL.
   * @apiSuccess {String}   type      file type: image, document, audio etc. etc.
   * @apiSuccess {String}   name      File mame.
   * @apiSuccess {Integer}  timestamp The timestamp when the file copied to the server.
   * @apiSuccess {String}   preview   The html representation of the file.
   * @apiSuccess {Object}   Terms     Terms attached to the file.
   */
  public static function controllersInfo() {
    return parent::controllersInfo();
  }

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
   * @api {post} api/files Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Files
   *
   * @apiDescription Upload a file.
   *
   * Unlike other data we can send via REST request files are a different
   * animal. You can use for example extension such as [Postman](https://chrome.google.com/webstore/detail/postman-rest-client/fdmmgilgnpjigdojojpjoooidkmcomcm)
   * or other tools to send to the file. The most important thing is that the
   * file object will be attached to the file property in the request.
   *
   * Once the request passed, you will get back a result representing the file
   * object in OpenScholar's database. In order to attach files to content
   * you'll need to pass the file ID from the file you uploaded.
   *
   * @apiSuccess {String} file The file object. Look on the post documentation.
   *
   * @apiSampleRequest off
   *
   * @apiExample {json} Example usage:
   *  $http.post(backend + 'files', {
   *    data: {
   *      file: fileObject
   *    },
   *    headers: {
   *      "X-CSRF-Token": "pgaSEyNaDELTBuPXy-Jpx_6I-mrEruxH3_-BEcMtnU0"
   *    }
   *  });
   *
   * @apiSuccessExample {json} Success-Response:
   *   {
   *    "data": [
   *      {
   *        "id": "14",
   *        "label": "picture0010.jpg",
   *        "self": "http://localhost/openscholar/api/v1.0/files/14",
   *        "size": "83830",
   *        "mimetype": "image/png",
   *        "url": "http://localhost/openscholar/sites/default/files/picture0010.jpg",
   *        "type": "image",
   *        "name": "picture0010.jpg",
   *        "timestamp": "83830",
   *        "description": null,
   *        "image_alt": null,
   *        "image_title": null,
   *        "preview": "field_view",
   *        "terms": null
   *      }
   *    ],
   *   }
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/files/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Files
   *
   * @apiDescription Delete a file entry.
   *
   * @apiParam {Number} id The file ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/bio/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Files
   *
   * @apiDescription Update a file entry.
   *
   * @apiParam {Number} id The publication ID
   *
   * @apiSampleRequest off
   *
   * @apiSuccess {String} file The file object. Look on the post documentation.
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }
}
