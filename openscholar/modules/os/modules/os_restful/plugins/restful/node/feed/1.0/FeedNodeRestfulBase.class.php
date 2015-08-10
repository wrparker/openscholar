<?php

class FeedNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/feed/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Feed
   *
   * @apiDescription Consume feed content.
   *
   * @apiParam {Number} id The feed ID
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

    $public_fields['field_url'] = array(
      'property' => 'field_url',
      'required' => TRUE,
    );

    return $public_fields;
  }

  /**
   * @api {post} api/feed Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Feed
   *
   * @apiDescription Create a feed entry.
   *
   * @apiParam {Number} id The feed ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/feed/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Feed
   *
   * @apiDescription Delete a feed entry.
   *
   * @apiParam {Number} id The feed ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/feed/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Feed
   *
   * @apiDescription Update a feed entry.
   *
   * @apiParam {Number} id The feed ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }

}
