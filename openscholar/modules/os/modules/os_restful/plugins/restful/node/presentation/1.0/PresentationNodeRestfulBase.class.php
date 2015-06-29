<?php

class PresentationNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/presentation/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Presentation
   *
   * @apiDescription Consume presentation content.
   *
   * @apiParam {Number} id The presentation ID
   *
   * @apiSuccess {Number}   id        The publication ID.
   * @apiSuccess {String}   label     Registration Date.
   * @apiSuccess {Object}   vsite     The vsite object.
   * @apiSuccess {String}   vsite.title     Group name.
   * @apiSuccess {Integer}  vsite.id        Group ID.
   * @apiSuccess {string}   body      The body of the publication.
   * @apiSuccess {Integer}  files.fid       file ID.
   * @apiSuccess {Integer}  files.filemime  Mime type.
   * @apiSuccess {Integer}  files.name      File name.
   * @apiSuccess {Integer}  files.uri       Uniform Resource Identifier.
   * @apiSuccess {Integer}  files.url       The address url.
   * @apiSuccess {String}   Date            The presentation date.
   * @apiSuccess {String}   location        The location of the presentation.
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['date'] = array(
      'property' => 'field_presentation_date',
      'process_callbacks' => array(
        array($this, 'dateProcess'),
      ),
    );

    $public_fields['location'] = array(
      'property' => 'field_presentation_location',
    );

    return $public_fields;
  }

  /**
   * @api {post} api/presentation Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Presentation
   *
   * @apiDescription Update a Presentation entry.
   *
   * @apiParam {Number} id The presentation ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/presentation/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Presentation
   *
   * @apiDescription Delete a Presentation entry.
   *
   * @apiParam {Number} id The presentation ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/presentation/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Presentation
   *
   * @apiDescription Update a Presentation entry.
   *
   * @apiParam {Number} id The presentation ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }

}
