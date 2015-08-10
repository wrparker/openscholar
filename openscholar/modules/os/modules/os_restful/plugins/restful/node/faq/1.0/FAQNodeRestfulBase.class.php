<?php

class FAQNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/faq/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup FAQ
   *
   * @apiDescription Consume faq content.
   *
   * @apiParam {Number} id The faq ID
   *
   * @apiSuccess {Number}   id              The publication ID.
   * @apiSuccess {String}   label           Registration Date.
   * @apiSuccess {Object} vsite           The vsite object.
   * @apiSuccess {String}   vsite.title     Group name.
   * @apiSuccess {Integer}  vsite.id        Group ID.
   * @apiSuccess {string}   body            The body of the publication.
   * @apiSuccess {Object} files           The attached files.
   * @apiSuccess {Integer}  files.fid       file ID.
   * @apiSuccess {Integer}  files.filemime  Mime type.
   * @apiSuccess {Integer}  files.name      File name.
   * @apiSuccess {Integer}  files.uri       Uniform Resource Identifier.
   * @apiSuccess {Integer}  files.url       The address url.
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    return $public_fields;
  }

  /**
   * {@inheritdoc}
   */
  public function entityPreSave(\EntityMetadataWrapper $wrapper) {
    parent::entityPreSave($wrapper);
    $request = $this->getRequest();
    $date = $wrapper->field_date->value();
    $format = 'Y-m-d h:i:s';
    if (!empty($request['start_date'])) {
      $date[0]['value'] = date($format, strtotime($request['start_date']));
    }

    $date[0]['value2'] = empty($request['end_date']) ? $date[0]['value'] : date($format, strtotime($request['end_date']));

    $wrapper->field_date->set($date);
  }

  /**
   * @api {post} api/faq Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup FAQ
   *
   * @apiDescription Create a faq entry.
   *
   * @apiParam {Number} id The faq ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/faq/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup FAQ
   *
   * @apiDescription Delete a faq entry.
   *
   * @apiParam {Number} id The faq ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/faq/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup FAQ
   *
   * @apiDescription Update a faq entry.
   *
   * @apiParam {Number} id The faq ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }
}
