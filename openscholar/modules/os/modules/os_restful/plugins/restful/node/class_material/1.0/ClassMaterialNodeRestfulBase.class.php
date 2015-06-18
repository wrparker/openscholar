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
