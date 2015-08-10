<?php

class BlogNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/blog/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Blog
   *
   * @apiDescription Consume blog content.
   *
   * @apiParam {Number} id The blog ID
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
   * @api {post} api/blog Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Blog
   *
   * @apiDescription Create a blog entry.
   *
   * @apiParam {Number} id The blog ID
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
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/blog/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Blog
   *
   * @apiDescription Delete a blog entry.
   *
   * @apiParam {Number} id The blog ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/blog/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Blog
   *
   * @apiDescription Update a blog entry.
   *
   * @apiParam {Number} id The blog ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }

}
