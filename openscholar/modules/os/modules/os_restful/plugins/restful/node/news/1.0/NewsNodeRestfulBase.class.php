<?php

class NewsNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/news/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup News
   *
   * @apiDescription Consume news content.
   *
   * @apiParam {Number} id The news ID
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['date'] = array(
      'property' => 'field_news_date',
    );

    $public_fields['photo'] = array(
      'property' => 'field_photo',
    );

    return $public_fields;
  }

  /**
   * @api {post} api/news Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup News
   *
   * @apiDescription Create a News entry.
   *
   * @apiParam {Number} id The news ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/news/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup News
   *
   * @apiDescription Create a News entry.
   *
   * @apiParam {Number} id The news ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/news/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup News
   *
   * @apiDescription Create a News entry.
   *
   * @apiParam {Number} id The news ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }

}
