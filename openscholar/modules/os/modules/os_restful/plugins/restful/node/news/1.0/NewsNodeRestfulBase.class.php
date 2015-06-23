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
   *
   * @apiSuccess {Number}   id              The publication ID.
   * @apiSuccess {String}   label           Registration Date.
   * @apiSuccess {Object[]} vsite           The vsite object.
   * @apiSuccess {String}   vsite.title     Group name.
   * @apiSuccess {Integer}  vsite.id        Group ID.
   * @apiSuccess {string}   body            The body of the publication.
   * @apiSuccess {Object[]} files           The attached files.
   * @apiSuccess {Integer}  files.fid       file ID.
   * @apiSuccess {Integer}  files.filemime  Mime type.
   * @apiSuccess {Integer}  files.name      File name.
   * @apiSuccess {Integer}  files.uri       Uniform Resource Identifier.
   * @apiSuccess {Integer}  files.url       The address url.
   * @apiSuccess {String}   date            The date which the news created.
   * @apiSuccess {String}   photo           The date which the news created.
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
   * @apiDescription Create a news entry.
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
   * @apiDescription Delete a news entry.
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
   * @apiDescription Update a news entry.
   *
   * @apiParam {Number} id The news ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }

}
