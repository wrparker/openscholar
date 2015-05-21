<?php

class PageNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/page/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Page
   *
   * @apiDescription Consume page content.
   *
   * @apiParam {Number} id The page ID
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['path'] = array(
      'property' => 'path',
    );

    return $public_fields;
  }

  /**
   * @api {post} api/page Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Page
   *
   * @apiDescription Create a Page entry.
   *
   * @apiParam {Number} id The page ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/page/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Page
   *
   * @apiDescription Create a Page entry.
   *
   * @apiParam {Number} id The page ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/page/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Page
   *
   * @apiDescription Create a Page entry.
   *
   * @apiParam {Number} id The page ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }


}
