<?php

class BookNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/book/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Book
   *
   * @apiDescription Consume book content.
   *
   * @apiParam {Number} id The book ID
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    return $public_fields;
  }

  /**
   * @api {post} api/book Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Book
   *
   * @apiDescription Create a book entry.
   *
   * @apiParam {Number} id The book ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/book/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Book
   *
   * @apiDescription Delete a book entry.
   *
   * @apiParam {Number} id The book ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/book/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Book
   *
   * @apiDescription Patch a book entry.
   *
   * @apiParam {Number} id The book ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }
}
