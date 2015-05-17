<?php

class BiblioNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/biblio/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Publication
   *
   * @apiDescription Consume publications content.
   *
   * @apiParam {Number} id The publication ID
   *
   * @apiSuccess {Number}   id        The publication ID.
   * @apiSuccess {String}   label     Registration Date.
   * @apiSuccess {Object}   vsite     The vsite object.
   * @apiSuccess {string}   body      The body of the publication.
   * @apiSuccess {Object[]} files     The attached files.
   * @apiSuccess {Integer}  type      The publication type ID.
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['type'] = array(
      'property' => 'biblio_type',
    );

    return $public_fields;
  }

  /**
   * @api {post} api/biblio Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Publication
   *
   * @apiDescription Create a Publication entry.
   *
   * @apiParam {Number} id The publication ID
   *
   * @apiSampleRequest off
   *
   * @apiSuccess {Number}   id        The publication ID.
   * @apiSuccess {String}   label     Registration Date.
   * @apiSuccess {Integer}  vsite     The vsite ID.
   * @apiSuccess {string}   body      The body of the publication.
   * @apiSuccess {Integer[]} files    List of file IDs.
   * @apiSuccess {Integer}  type      The publication type ID.
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/biblio/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Publication
   *
   * @apiDescription Create a Publication entry.
   *
   * @apiParam {Number} id The publication ID
   *
   * @apiSampleRequest off
   *
   * @apiSuccess {Number}   id        The publication ID.
   * @apiSuccess {String}   label     Registration Date.
   * @apiSuccess {Integer}  vsite     The vsite ID.
   * @apiSuccess {string}   body      The body of the publication.
   * @apiSuccess {Integer[]} files    List of file IDs.
   * @apiSuccess {Integer}  type      The publication type ID.
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/biblio/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Publication
   *
   * @apiDescription Create a Publication entry.
   *
   * @apiParam {Number} id The publication ID
   *
   * @apiSampleRequest off
   *
   * @apiSuccess {Number}   id        The publication ID.
   * @apiSuccess {String}   label     Registration Date.
   * @apiSuccess {Integer}  vsite     The vsite ID.
   * @apiSuccess {string}   body      The body of the publication.
   * @apiSuccess {Integer[]} files    List of file IDs.
   * @apiSuccess {Integer}  type      The publication type ID.
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }
}
