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
   * @apiDescription Create a faq entry.
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
   * @apiDescription Create a faq entry.
   *
   * @apiParam {Number} id The faq ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }
}
