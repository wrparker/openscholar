<?php

class ClassMaterialNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} api/class_material/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Class material
   *
   * @apiDescription Consume class material content.
   *
   * @apiParam {Number} id The class ID
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
   * @apiSuccess {Object}   parent          The class which the current object
   *                                        belong to.
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['parent'] = array(
      'property' => 'field_class',
    );

    return $public_fields;
  }
  /**
   * @api {post} api/class_material Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Class material
   *
   * @apiDescription Create a class material entry.
   *
   * @apiParam {Number} id The class ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/class_material/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Class material
   *
   * @apiDescription Delete a class entry.
   *
   * @apiParam {Number} id The class material ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/class_material/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Class material
   *
   * @apiDescription Update a class material entry.
   *
   * @apiParam {Number} id The class ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }

}
