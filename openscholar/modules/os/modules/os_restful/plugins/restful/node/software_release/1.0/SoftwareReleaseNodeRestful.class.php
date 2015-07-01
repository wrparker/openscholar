<?php

class SoftwareReleaseNodeRestful extends OsNodeRestfulBase {

  /**
   * Return the release name and id.
   */
  public function softwareProjectPreprocess($value) {
    return array(
      'id' => $value->nid,
      'label' => $value->title,
    );
  }

  public function softwareProjectRecommended($value) {
    return $value ? t('Recommended') : t('Not Recommended');
  }

  /**
   * @api {get} api/software_release/:id Get
   * @apiVersion 0.1.0
   * @apiName Get
   * @apiGroup Software release
   *
   * @apiDescription Consume software release content.
   *
   * @apiParam {Number} id The software release ID
   *
   * @apiSuccess {Number}   id                      The software release ID.
   * @apiSuccess {String}   label                   The publication ID.
   * @apiSuccess {object}   vsite                   The vsite object
   * @apiSuccess {String}   vsite.title             Group name.
   * @apiSuccess {Integer}  vsite.id                Group ID.
   * @apiSuccess {Object}   software_release        The software project reference.
   * @apiSuccess {Object}   software_release.id     Software project ID
   * @apiSuccess {Object}   software_release.label  Software project label
   * @apiSuccess {Bool}     recommended             If this release recommended or not
   * @apiSuccess {String}   version                 The version of the release
   * @apiSuccess {Object}   package                 The file object
   * @apiSuccess {Object}   package.fid             File ID
   * @apiSuccess {Object}   package.filemime        File filemime.
   * @apiSuccess {Object}   package.name            File name.
   * @apiSuccess {Object}   package.uri             URI of the file.
   * @apiSuccess {Object}   package.url             File URL.
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['software_release'] = array(
      'property' => 'field_software_project',
      'process_callbacks' => array(
        array($this, 'softwareProjectPreprocess'),
      ),
    );

    $public_fields['recommended'] = array(
      'property' => 'field_software_recommended',
      'process_callbacks' => array(
        array($this, 'softwareProjectRecommended'),
      ),
    );

    $public_fields['version'] = array(
      'property' => 'field_software_version',
    );

    $public_fields['package'] = array(
      'property' => 'field_software_package',
      'process_callbacks' => array(
        array($this, 'singleFileFieldDisplay'),
      ),
    );

    unset($body);

    return $public_fields;
  }

  /**
   * @api {post} api/software_release Post
   * @apiVersion 0.1.0
   * @apiName Post
   * @apiGroup Software release
   *
   * @apiDescription Create a software release entry.
   *
   * @apiParam {Number} id The software release ID
   *
   * @apiSampleRequest off
   */
  public function createEntity() {
    return parent::createEntity();
  }

  /**
   * @api {delete} api/software_release/:id Delete
   * @apiVersion 0.1.0
   * @apiName Delete
   * @apiGroup Software release
   *
   * @apiDescription Delete a software release entry.
   *
   * @apiParam {Number} id The software release ID
   *
   * @apiSampleRequest off
   */
  public function deleteEntity($entity_id) {
    parent::deleteEntity($entity_id);
  }

  /**
   * @api {patch} api/software_release/:id Patch
   * @apiVersion 0.1.0
   * @apiName Patch
   * @apiGroup Software release
   *
   * @apiDescription Update a software release entry.
   *
   * @apiParam {Number} id The software release ID
   *
   * @apiSampleRequest off
   */
  public function patchEntity($entity_id) {
    parent::patchEntity($entity_id);
  }

}
