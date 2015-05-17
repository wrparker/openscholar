<?php

class BiblioNodeRestfulBase extends OsNodeRestfulBase {

  /**
   * @api {get} /biblio/:id Biblio
   * @apiVersion 0.1.0
   * @apiName GetEndpoints
   * @apiGroup Content
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
}
