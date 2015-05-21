<?php

class LinkNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/link/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Link
   *
   * @apiDescription Consume link content.
   *
   * @apiParam {Number} id The link ID
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['url'] = array(
      'property' => 'field_links_link',
      'sub_property' => 'url',

    );

    return $public_fields;
  }

  /**
   * @api {post} api/link Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Link
   *
   * @apiDescription Create a Link entry.
   *
   * @apiParam {Number} id The link ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/link/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Link
   *
   * @apiDescription Create a Link entry.
   *
   * @apiParam {Number} id The link ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/link/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Link
   *
   * @apiDescription Create a Link entry.
   *
   * @apiParam {Number} id The link ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }

}
